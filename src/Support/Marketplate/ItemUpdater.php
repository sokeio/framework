<?php

namespace Sokeio\Support\Marketplate;

use Illuminate\Support\Facades\File;
use Sokeio\Marketplate;
use Sokeio\Platform;
use Sokeio\Support\Platform\ItemInfo;

class ItemUpdater
{
    private ItemInfo $itemInfo;
    private $backupPath;
    private $name;
    private BaseProvider $baseProvider;
    public function __construct(
        protected $data,
        protected $type,
    ) {
        $id = data_get($this->data, 'id');
        $provider_type = data_get($this->data, 'provider_type');
        $this->name = data_get($this->data, 'name');
        $this->baseProvider = Marketplate::getProvider($provider_type, $this->data);
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
            $this->baseProvider->extractZip($pathCurent);
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
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            return false;
        }
    }
}
