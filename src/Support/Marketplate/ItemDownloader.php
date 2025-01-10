<?php

namespace Sokeio\Support\Marketplate;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ItemDownloader
{
    public function __construct(
        private array $data

    ) {}
    public function getDataValue($key, $default = null)
    {
        if (!$this->data) {
            return $default;
        }
        return data_get($this->data, $key, $default);
    }
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
    public function extractZip($path)
    {
        $filename = time() . '.zip';
        $pathFile = config('sokeio.platform.updater.temp') . $filename;
        Log::info(['pathFile' => $pathFile]);
        // extract the zip file
        $flg = $this->saveAs($pathFile);
        if ($flg) {
            $zip = new \ZipArchive;
            $res = $zip->open($pathFile);
            if ($res === TRUE) {
                $zip->extractTo($path);
                $zip->close();
                Log::info('extractZip:' . $path);
                File::delete($pathFile);
                return true;
            } else {
                Log::info('extractZip error:' . $path);
                File::delete($pathFile);
                return false;
            }
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
}
