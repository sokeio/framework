<?php

namespace Sokeio\Support\Marketplate;

use Illuminate\Support\Facades\File;

class BaseUpdater
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
        return false;
    }
    public function extractZip($path)
    {
        $filename = time() . '.zip';
        $pathFile = $path . '/' . $filename;
        // extract the zip file
        $flg = $this->saveAs($pathFile);
        if ($flg) {
            $zip = new \ZipArchive;
            $res = $zip->open($pathFile);
            if ($res === TRUE) {
                $zip->extractTo($path);
                $zip->close();
                File::delete($pathFile);
                return true;
            } else {
                File::delete($pathFile);
                return false;
            }
        }
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
