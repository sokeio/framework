<?php

namespace Sokeio\Support\Marketplate;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Sokeio\Platform;
use Sokeio\Support\Platform\ItemInfo;

class ItemUpdater
{
    private ItemInfo $itemInfo;
    private $backupPath;
    private $itemNewPath;
    private $extractPath;
    private $name;
    private $id;
    private $folderId;
    private $version;
    private ItemDownloader $itemDownloader;
    public function __construct(
        protected $data,
        protected $type,
    ) {
        $id = data_get($this->data, 'id');
        $this->folderId = str_replace('/', '-', $id);
        $this->version = data_get($this->data, 'version');
        $this->id = $id;
        $this->name = data_get($this->data, 'name');
        $this->itemDownloader = new ItemDownloader($this->data);
        if ($this->type === 'module') {
            $this->itemInfo = Platform::module()->findByNameOrId($id);
        } else if ($this->type === 'theme') {
            $this->itemInfo = Platform::theme()->findByNameOrId($id);
        } else if ($this->type === 'package') {
            // Not implemented yet
            // $this->itemInfo = Platform::package()->findByNameOrId($this->id);
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
    public function download()
    {
        $this->extractPath = config('sokeio.platform.updater.temp') . $this->folderId . '-' . $this->itemInfo->getVersion() . '-' . time();
        Log::info('update3:' . $this->extractPath);
        $this->itemDownloader->extractZip($this->extractPath);
        $this->itemNewPath = $this->itemInfo->getManager()->getPathWithFileTypeInfo($this->extractPath);
    }
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
}
