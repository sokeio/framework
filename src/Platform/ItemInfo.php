<?php

namespace Sokeio\Platform;

use Illuminate\Support\Facades\File;
use Sokeio\ObjectJson;
use Sokeio\Platform;
use Sokeio\Platform\Concerns\WithRegisterItemInfo;
use Sokeio\ServicePackage;

class ItemInfo extends ObjectJson
{
    use WithRegisterItemInfo;
    private ServicePackage|null $package = null;
    private $flgActive = null;
    private $flgVendor = null;
    public function getPackage(): ServicePackage|null
    {
        return $this->package;
    }
    public function setPackage(ServicePackage $package): self
    {
        $this->package = $package;
        return $this;
    }

    public function __construct(private $path, private $manager, $data = [])
    {
        $this->path = realpath($this->path);
        parent::__construct($data);
    }
    public function getManager(): ItemManager
    {
        return $this->manager;
    }

    public function getPath()
    {
        return $this->path;
    }
    public function isVendor(): bool
    {
        if ($this->flgVendor === null) {
            $this->flgVendor = Platform::isVendor($this->path);
        }
        return $this->flgVendor;
    }
    public function isActive(): bool
    {
        if ($this->flgActive === null) {
            $this->flgActive = $this->manager->isActive($this);
        }
        return $this->flgActive;
    }
    public function setActive(): bool
    {
        return $this->manager->setActive($this);
    }
    public function block(): void
    {
        $this->manager->block($this);
    }
    public function getLayouts(): array
    {
        return  collect(File::allFiles($this->path . '/resources/views/layouts'))->map(function ($file) {
            // remove .blade.php
            return str_replace('.blade.php', '', $file->getRelativePathname());
        })->toArray();
    }
    /**
     * Copy the public folder of this item to the public_path('platform/$itemType/$name')
     * if not exists.
     *
     * @return $this
     */
    public function makePublic()
    {
        $pathPublic = $this->path . '/public';
        if (file_exists($pathPublic)) {
            $pathTarget = public_path('platform/' . $this->getManager()->getItemType() . 's/' . $this->name);
            if (!file_exists($pathTarget)) {
                File::copyDirectory($pathPublic, $pathTarget);
            }
        }
        return $this;
    }
}
