<?php

namespace Sokeio\Marketplate;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Sokeio\Platform;
use Sokeio\Core\ItemInfo;

class ItemInstaller
{
    private ItemInfo $itemInfo;
    private $backupPath;
    protected $itemNewPath;
    private $name;
    private $id;
    private $folderId;
    private $version;
    public function getDataValue($key, $default = null)
    {
        if (!$this->data) {
            return $default;
        }
        return data_get($this->data, $key, $default);
    }
    public function __construct(
        protected $data,
        protected $type,
    ) {
        $this->init();
    }
    public function getItemInfo()
    {
        return $this->itemInfo;
    }
    public function getFolderId()
    {
        return $this->folderId;
    }

    protected function init()
    {

        $id = data_get($this->data, 'id');
        $this->folderId = str_replace('/', '-', $id);
        $this->version = data_get($this->data, 'version');
        $this->id = $id;
        $this->name = data_get($this->data, 'name');
        if ($this->type === 'module') {
            $this->itemInfo = Platform::module()->find($id);
        } else if ($this->type === 'theme') {
            $this->itemInfo = Platform::theme()->find($id);
        } else if ($this->type === 'package') {
            // Not implemented yet
            // $this->itemInfo = Platform::package()->find($this->id);
        }
        $this->backupPath = config('sokeio.platform.backup');
    }
    public function getName()
    {
        return $this->name;
    }
    public function backup()
    {
        Log::info('backup:' . $this->id);
        // backup the current version
        if ($this->itemInfo) {
            $pathCurent = $this->itemInfo->getPath();
            $this->backupPath = config('sokeio.platform.updater.backup') .  $this->folderId . '-' . $this->itemInfo->getVersion() . '-' . time();
            Log::info('backupPath:' . $this->backupPath);
            if (File::exists($this->backupPath)) {
                File::deleteDirectory($this->backupPath);
            }
            File::makeDirectory($this->backupPath);
            File::copyDirectory($pathCurent,  $this->backupPath);
        }
    }
    public function processNew() {}

    public function update()
    {
        if ($this->itemInfo && $this->itemNewPath) {
            Log::info('update:' . $this->id);
            $pathCurent = $this->itemInfo->getPath();
            File::deleteDirectory($pathCurent);
            File::copyDirectory($this->itemNewPath, $pathCurent);
            File::deleteDirectory($this->itemNewPath);
            $this->itemInfo->updateVersion($this->version);
            return true;
        }
        return false;
        // update the current version

    }
    public function rollback()
    {
        // rollback the current version
        Log::info('rollback:' . $this->id);
        if ($this->backupPath) {
            $pathCurent = $this->itemInfo->getPath();
            File::deleteDirectory($pathCurent);
            File::copyDirectory($this->backupPath, $pathCurent);
            File::deleteDirectory($this->backupPath);
        }
    }
    protected function extractZip($path, $pathFile)
    {
        // extract the zip file
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
        return false;
    }
}
