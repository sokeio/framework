<?php

namespace Sokeio\Support\Updater;

use Sokeio\Platform;
use Sokeio\Support\Platform\ItemInfo;

class UpdaterInfo
{
    private ItemInfo $itemInfo;
    private $backupPath;
    public function __construct(
        private string $type, // module, theme, pacakge
        private string $id, // organization/key
        private string $version,
        private string $serviceType,
        private string $serviceData
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
    }
    public function update()
    {
        // update the current version
    }
    public function rollback()
    {
        // rollback the current version
    }
    public function doUpdate()
    {
        // do the update
        try {
            $this->backup();
            $this->update();
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }
    }
}
