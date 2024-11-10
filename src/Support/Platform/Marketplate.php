<?php

namespace Sokeio\Support\Platform;

use Directory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Sokeio\Platform;

class Marketplate
{
    private $marketplaceUrl = 'http://sokeio.local/api/';
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

        return Http::post($url, $data);
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
        // move $id(sokeio/cms) => $folder_target(sokeio-cms-{version})
        $folder_target = str($id)->replace('/', '-') . '-' . $version;
        $path_folder_target = $this->manager->getPath($folder_target);
        if (file_exists($path_folder_target)) {
            File::deleteDirectory($path_folder_target);
        }
        $old = $this->manager->find($id);
        if ($old) {
            $this->manager->delete($id);
        }
        File::copyDirectory($folder_main, $path_folder_target);
        return true;
    }
    public function uninstall($id)
    {
        //TODO : implement uninstall
    }
    public function search($query)
    {
        //TODO : implement search
    }
}
