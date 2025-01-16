<?php

namespace Sokeio\Core;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Marketplate
{
    private $marketplaceUrl = 'https://sokeio.local/';
    private $pathTemps = 'platform/temps/';
    public function __construct(private ItemManager $manager)
    {
        // $this->marketplaceUrl = config('sokeio.platform.base_url', $this->marketplaceUrl);
        $this->pathTemps = config('sokeio.platform.temps', $this->pathTemps);
        if (!file_exists(base_path($this->pathTemps))) {
            File::makeDirectory(base_path($this->pathTemps), 0775, true);
            file_put_contents(base_path($this->pathTemps . '.gitkeep'), '');
        }
    }
    private function makeRequest(string $url, array $data = []): \Illuminate\Http\Client\Response
    {
        $url = sprintf('%s%s', $this->marketplaceUrl, $url);
        return Http::withOptions(['verify' => false])->post($url, $data);
    }
    public function getLastVersion($id, $version = 'main')
    {
        $rs = $this->makeRequest('platform/version', [
            'id' => $id,
            'version' => $version,
            'type' => $this->manager->getItemType()
        ]);
        if ($rs->ok()) {
            return $rs->json('version', $version);
        }
        return $version;
    }
    public function install($id, $version = 'main')
    {
        $filename = $this->manager->getItemType() . '-' . time() . '.zip';
        $pathFile = base_path($this->pathTemps . $filename);
        $rs = $this->makeRequest('platform/updater', [
            'id' => $id,
            'version' => $version,
            'type' => $this->manager->getItemType()
        ]);
        if ($rs->ok()) {

            file_put_contents($pathFile, $rs->body());
            if (file_exists($pathFile)) {
                $this->installFromPath($pathFile);
            }
            return true;
        }
        return false;
    }
    public function installFromPath($path)
    {
        $folder = $path . '-folder';
        File::makeDirectory($folder);
        $zip = new \ZipArchive();
        $zip->open($path, \ZipArchive::CHECKCONS);
        $zip->extractTo($folder);
        $zip->close();
        $type = $this->manager->getItemType() . '.json';
        $file = collect(File::allFiles($folder))->filter(function ($file) use ($type) {
            // CHeck File Type
            return $file->getFilename() == $type;
        })->first();
        $folder_main = $file->getPath();
        $json = json_decode($file->getContents(), true);
        $id = $json['id'];
        $version = str($json['version'])->replace('.', '-');
        // move $id(sokeio/content) => $folder_target(sokeio-content-{version})
        $folder_target = str($id)->replace('/', '-') . '-' . $version;
        $path_folder_target = $this->manager->getPath($folder_target);
        if (file_exists($path_folder_target)) {
            File::deleteDirectory($path_folder_target);
        }
        $this->uninstall($id);
        File::copyDirectory($folder_main, $path_folder_target);
        //Remove temps
        File::delete($path);
        File::deleteDirectory($folder);
        return true;
    }
    public function uninstall($id)
    {
        $old = $this->manager->find($id);
        if ($old) {
            $old->block();
            $old->delete();
        }
    }
    public function search($query)
    {
        $rs = $this->makeRequest('platform/search', [
            'query' => $query,
            'type' => $this->manager->getItemType()
        ]);
        if ($rs->ok()) {
            return $rs->json();
        }
        return [];
    }
}
