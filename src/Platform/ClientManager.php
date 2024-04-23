<?php

namespace Sokeio\Platform;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use ZipArchive;

class ClientManager
{
    private $domain = 'https://sokeio.com/api/marketplace/';
    private $versionApi = 'v1';
    private $productId = 'sokeio';
    private $licenseKey = 'jx26dpclu8d7vfhyjgsnlhviezcan612';
    private $licenseToken = '';
    private $licenseInfo = [];
    private $sokeioInfo = [];
    private const PATH_LICENSE = 'platform/license.json';
    private \Illuminate\Http\Client\PendingRequest $client;

    private function initClient()
    {
        if (file_exists(base_path('platform/sokeio.json'))) {
            $this->sokeioInfo = json_decode(File::get(base_path('platform/sokeio.json')), true) ?? [];
        }
        if (isset($this->sokeioInfo['productId']) && $this->sokeioInfo['productId']) {
            $this->productId = $this->sokeioInfo['productId'];
        }
        $this->resetClient();
    }
    private function resetClient()
    {
        if (file_exists(base_path(self::PATH_LICENSE))) {
            $this->licenseInfo = json_decode(File::get(base_path(self::PATH_LICENSE)), true) ?? [];
        }
        if (isset($this->licenseInfo['data']['token']) && $this->licenseInfo['data']['token']) {
            $this->licenseToken = $this->licenseInfo['data']['token'];
        }
        if (isset($this->licenseInfo['data']['key']) && $this->licenseInfo['data']['key']) {
            $this->licenseKey = $this->licenseInfo['data']['key'];
        }
        $this->client = Http::withHeaders([
            'X-License-Product' => $this->productId,
            'X-License-Key' => $this->licenseKey,
            'X-License-Token' => $this->licenseToken,
            'X-License-Domain' => $_SERVER['HTTP_HOST'],
            'Accept' => 'application/vnd.api+json',
        ])->baseUrl($this->domain .  $this->versionApi);
    }

    public function __construct()
    {
        $this->domain = env('SOKEIO_PLATFORM_API', 'https://sokeio.com/api/');
        $this->initClient();
    }
    public function getProductId()
    {
        return $this->productId;
    }
    public function sokeioInstall()
    {
        if (isset($this->sokeioInfo['modules']) && is_array($this->sokeioInfo['modules'])) {
            $this->installLastVersion($this->sokeioInfo['modules'], 'module');
        }
        if (isset($this->sokeioInfo['themes']) && is_array($this->sokeioInfo['themes'])) {
            $this->installLastVersion($this->sokeioInfo['themes'], 'theme');
        }
    }
    public function getLastVersion($arrs = [], $type = 'module')
    {
        return $this->client->post('marketplace/latest-version', [
            'type' => $type,
            'data' => $arrs
        ]);
    }
    public function installLastVersion($arrs = [], $type = 'module')
    {
        $response = $this->getLastVersion($arrs, $type);
        foreach ($response->json('data') as $item) {
            $this->install($item, $type);
        }
    }
    public function install($packageToken, $type = 'module')
    {
        if (!file_exists(storage_path('temps'))) {
            mkdir(storage_path('temps'));
        }
        $pathFileTemp = storage_path('temps') . '/' . time() . '.zip';
        $this->client->sink($pathFileTemp)->post('marketplace/download', [
            'type' => $type,
            'data' => $packageToken
        ]);
        $this->installLocal($pathFileTemp);
        File::delete($pathFileTemp);
    }
    public function installLocal($path, $type = 'module')
    {
        if (!File::exists($path)) {
            return;
        }
        $zip = new ZipArchive();
        if ($zip->open($path) !== true) {
            return;
        }
        $pathFolder = storage_path('temps') . '/folder-' . time() . '';
        mkdir($pathFolder);
        $zip->extractTo($pathFolder);
        $zip->close();
        $pathLocalTemp = '';
        $fileInfo = [];
        foreach (File::allFiles($pathFolder) as $file) {
            if ($file->getFilename() === $type . '.json') {
                $pathLocalTemp = $file->getPath();
                $fileInfo = json_decode($file->getContents(), true);
                break;
            }
        }
        if (empty($pathLocalTemp) || !in_array($type, ['module', 'theme']) || !isset($fileInfo['id'])) {
            return;
        }
        $baseId = $fileInfo['id'];
        $baseInSystem = platformBy($type)->find($baseId);
        if ($baseInSystem) {
            File::deleteDirectory($baseInSystem->getPath());
        }

        $pathMoveTo = platformBy($type)->getPath($fileInfo['name']);
        if (File::exists($pathMoveTo)) {
            $pathMoveTo = $pathMoveTo . '-' . time();
        }
        File::copyDirectory($pathLocalTemp, $pathMoveTo);
        File::deleteDirectory($pathLocalTemp);
    }
    public function checkLicenseKey($key)
    {
        $rs = $this->client->post('product/activation', [
            'key' => $key
        ])->json();
        if ($rs && isset($rs['status']) && $rs['status'] == 1) {
            File::put(base_path(self::PATH_LICENSE), json_encode($rs));
        }
        return $rs;
    }
    public function checkLicense()
    {
        $this->resetClient();
        $rs = $this->client->post('product/verify')->json();
        return isset($rs['status']) && $rs['status'] == 1;
    }

    public function getLicense()
    {
        return [
            'status' => $this->checkLicense(),
            'data' => $this->licenseInfo ? $this->licenseInfo['data'] : null
        ];
    }
}
