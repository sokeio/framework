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
    private $licenseInfo = [];
    private $sokeioInfo = [];
    private \Illuminate\Http\Client\PendingRequest $client;
    private function initClient()
    {
        if (file_exists(base_path('platform/sokeio.json'))) {
            $this->sokeioInfo = json_decode(File::get(base_path('platform/sokeio.json')), true) ?? [];
        }
        $this->client = Http::withHeaders([
            'X-License-Product' => $this->productId,
            'X-License-Key' => $this->licenseKey,
            'X-License-Domain' => $_SERVER['HTTP_HOST'],
            'Accept' => 'application/vnd.api+json',
        ])->baseUrl($this->domain .  $this->versionApi);
    }
    public function __construct()
    {
        $this->domain = env('SOKEIO_PLATFORM_API', 'https://sokeio.com/api/marketplace/');
        $this->initClient();
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
        return $this->client->post('latest-version', [
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
        $this->client->sink($pathFileTemp)->post('download', [
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



        $pathFolder = storage_path('temps/folder-1712046286');
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
        $response = $this->client->post('check-license-key', [
            'key' => $key
        ]);
        return $response->json('data');
    }
    public function checkLicense()
    {
        if (file_exists(base_path('platform/license.json'))) {
            $this->licenseInfo = json_decode(File::get(base_path('platform/license.json')), true) ?? [];
        }
        if (!isset($this->licenseInfo['token']) || !$this->licenseInfo['token']) {
            return false;
        }
        $response = $this->client->post('check-license', [
            'token' => $this->licenseInfo['token']
        ]);
        return $response->json('data') != null;
    }

    public function getLicense()
    {
        if (!$this->checkLicense()) {
            return false;
        }
        return $this->licenseInfo;
    }
}
