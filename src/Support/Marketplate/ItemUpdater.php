<?php

namespace Sokeio\Support\Marketplate;

use Illuminate\Support\Facades\File;
use Sokeio\Platform;
use Sokeio\Support\Platform\ItemInfo;

class ItemUpdater
{
    private ItemInfo $itemInfo;
    private $backupPath;
    public function __construct(
        private string $type, // module, theme, pacakge
        private string $id, // organization/key
        private string $version, // version
        private BaseUpdater $serviceUpdater
    ) {
        if ($this->type === 'module') {
            $this->itemInfo = Platform::module()->findByNameOrId($this->id);
        } else if ($this->type === 'theme') {
            $this->itemInfo = Platform::theme()->findByNameOrId($this->id);
        } else if ($this->type === 'package') {
            // Not implemented yet
            // $this->itemInfo = Platform::package()->findByNameOrId($this->id);
        }
        $this->backupPath = config('sokeio.platform.backup');
    }
    public function backup()
    {
        // backup the current version
        if ($this->itemInfo) {
            $pathCurent = $this->itemInfo->getPath();
            $this->backupPath = config('sokeio.platform.backup') . '/' . $this->itemInfo->getId() . '-' . $this->itemInfo->getVersion() . '-' . time();
            File::copyDirectory($pathCurent,  $this->backupPath);
        }
    }
    public function update()
    {
        if ($this->itemInfo) {
            $pathCurent = $this->itemInfo->getPath();
            File::deleteDirectory($pathCurent);
            $this->serviceUpdater->extractZip($pathCurent);
        }
        // update the current version

    }
    public function rollback()
    {
        // rollback the current version
        if ($this->backupPath) {
            $pathCurent = $this->itemInfo->getPath();
            File::deleteDirectory($pathCurent);
            File::copyDirectory($this->backupPath, $pathCurent);
            File::deleteDirectory($this->backupPath);
        }
    }
    public function doUpdate()
    {
        // do the update
        try {
            $this->backup();
            $this->update();
            if ($this->backupPath) {
                File::deleteDirectory($this->backupPath);
            }
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }
    }
}
