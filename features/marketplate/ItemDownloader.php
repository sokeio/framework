<?php

namespace Sokeio\Marketplate;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ItemDownloader extends ItemInstaller
{
    private $extractPath;
   
    // download the zip file stream
    public function downloadZip(): mixed
    {
        // download the zip file
        $downloadUrl = $this->getDataValue('download_url');
        if ($downloadUrl) {
            $response = Http::withoutVerifying()->get($downloadUrl);
            if ($response->ok()) {
                return $response->body();
            }
            Log::info('downloadZip error:' . $downloadUrl);
            Log::info($response->body());
        }
        return false;
    }
    public function saveAs($path)
    {
        $body = $this->downloadZip();
        if (!$body) {
            return false;
        }
        file_put_contents($path, $body);
        // save the file as
        return true;
    }
    public function processNew()
    {
        $this->extractPath = config('sokeio.platform.updater.temp') . $this->getFolderId() . '-' . $this->getItemInfo()->getVersion() . '-' . time();
        $filename = time() . '.zip';
        $pathFile = config('sokeio.platform.updater.temp') . $filename;
        $this->extractZip($pathFile, $this->extractPath);
        $this->itemNewPath = $this->getItemInfo()->getManager()->getPathWithFileTypeInfo($this->extractPath);
    }
}
