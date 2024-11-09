<?php

namespace Sokeio\Support\Platform;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

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
            sleep(3);
            if (file_exists($pathFile)) {
                $this->installFromPath($pathFile);
            }
            return true;
        }
        return false;
    }
    public function installFromPath($path)
    {
        $zip = new \ZipArchive();
        $zip->open($path, \ZipArchive::CHECKCONS);
        $zip->extractTo(base_path($this->pathTemps));
        $zip->close();
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
