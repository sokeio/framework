<?php

namespace Sokeio\Support\Platform\Concerns;

use Illuminate\Support\Facades\File;
use Sokeio\Support\Platform\ItemManager;

trait WithPlatform
{
    private $urlAdmin;
    private $version;
    private $pathPlatform;
    private $callbackBooting = [];
    private $callbackBooted = [];
    private $logoFull;
    private $logo;
    public function version()
    {
        return $this->version ?? ($this->version = config('sokeio.version'));
    }
    public function logoFull($with = '100%')
    {
        if (!$this->logoFull && File::exists(__DIR__ . '/../../../../public/sokeio-full.svg')) {
            $this->logoFull = file_get_contents(__DIR__ . '/../../../../public/sokeio-full.svg');
        }
        return str($this->logoFull ?? '')->replace('width="100%"', 'width="' . $with . '"')->toString();
    }
    public function logo($with = '100%')
    {
        if (!$this->logo && File::exists(__DIR__ . '/../../../../public/sokeio.svg')) {
            $this->logo = file_get_contents(__DIR__ . '/../../../../public/sokeio.svg');
        }
        return str($this->logo ?? '')->replace('width="100%"', 'width="' . $with . '"')->toString();
    }
    public function screenshot($type, $id)
    {
        if (!$item = ItemManager::getInstance($type)->find($id)) {
            return null;
        }

        if (File::exists($item->getPath('screenshot.png'))) {

            return response()->file($item->getPath('screenshot.png'));
        }
        return response()->file(__DIR__ . '/../../../../public/sokeio.jpg');
    }
    public function adminUrl()
    {
        return $this->urlAdmin ?? ($this->urlAdmin = config('sokeio.admin_url'));
    }
    public function getPlatformPath()
    {
        return $this->pathPlatform ?? ($this->pathPlatform = base_path('platform'));
    }
    public function booting($callback)
    {
        $this->callbackBooting[] = $callback;
    }

    public function booted($callback)
    {
        $this->callbackBooted[] = $callback;
    }
    public function boot()
    {
        foreach ($this->callbackBooting as $callback) {
            $callback();
        }
        $this->callbackBooting = [];
        $this->module()->loader();
        $this->theme()->loader();
        $this->module()->boot();
        $this->theme()->boot();
        foreach ($this->callbackBooted as $callback) {
            $callback();
        }
        $this->callbackBooted = [];
    }


    public function theme()
    {
        return ItemManager::getInstance('theme');
    }
    public function module()
    {
        return ItemManager::getInstance('module');
    }
    public function loadFromPath($path)
    {
        $this->module()->loadFromPath($path);
        $this->theme()->loadFromPath($path);
    }

    public function loadFromServicePackage(\Sokeio\ServicePackage $servicePackage)
    {
        $path = $servicePackage->basePath('../');
        if ($item = $this->theme()->addFromPath($path)) {
            return $item->setPackage($servicePackage);
        }
        if ($item = $this->module()->addFromPath($path)) {
            return $item->setPackage($servicePackage);
        }
        return null;
    }
}
