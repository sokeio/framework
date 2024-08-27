<?php

namespace Sokeio\Platform;

use Sokeio\ObjectJson;
use Sokeio\Platform;
use Sokeio\Platform\Concerns\WithRegisterItemInfo;
use Sokeio\ServicePackage;

class ItemInfo extends ObjectJson
{
    use WithRegisterItemInfo;
    private ServicePackage $package;
    private $flgActive = null;
    private $flgVendor = null;
    public function getPackage(): ServicePackage
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
    public function loader(): void
    {
        Platform::applyLoader($this);
    }
}
