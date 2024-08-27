<?php

namespace Sokeio\Platform;

use Sokeio\ObjectJson;
use Sokeio\Platform;
use Sokeio\Platform\Concerns\WithRegisterItemInfo;
use Sokeio\PlatformLoader;
use Sokeio\ServicePackage;

class ItemInfo extends ObjectJson
{
    use WithRegisterItemInfo;
    private ServicePackage $package;
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
    public function getPath()
    {
        return $this->path;
    }
    public function isVendor(): bool
    {
        return !str($this->path)->startsWith(Platform::platformPath());
    }
    public function isActive(): bool
    {
        return $this->manager->isActive($this);
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
        PlatformLoader::apply($this);
    }
}
