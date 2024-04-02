<?php

namespace Sokeio\Updater;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Ramsey\Uuid\Guid\Fields;
use ZipArchive;

class UpdaterManager
{
    private $domain = 'http://sokeio.local/api/marketplace/'; // 'https://api.sokeio.com';
    private $versionApi = 'v1';
    private $productId = 'sokeio';
    private $licenseKey = 'jx26dpclu8d7vfhyjgsnlhviezcan612';
    private \Illuminate\Http\Client\PendingRequest $client;
    private function initClient()
    {
        $this->client = Http::withHeaders([
            'X-License-Product' => $this->productId,
            'X-License-Key' => $this->licenseKey,
            'X-License-Domain' => config('app.url'),
            'Accept' => 'application/vnd.api+json',
        ])->baseUrl($this->domain .  $this->versionApi);
    }
    public function __construct()
    {
        $this->initClient();
    }
    public function sokeioInstall()
    {
        $path = base_path('platform/sokeio.json');
        if (!File::exists($path)) {
            return;
        }
        $sokeioInfo = json_decode(File::get($path), true);
        if (isset($sokeioInfo['modules']) && is_array($sokeioInfo['modules'])) {
            $this->installLastVersion($sokeioInfo['modules'], 'module');
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
}
