<?php

namespace Sokeio\Platform;

use Illuminate\Pipeline\Pipeline;
use Sokeio\Platform\Loader\PageLoader;

class PlatformManager
{
    private $pipelineLoader = [
        PageLoader::class
    ];
    private $callbackBooting = [];
    private $callbackBooted = [];
    private $urlAdmin;
    private $pathPlatform;
    public function addLoader($loader)
    {
        $this->pipelineLoader[] = $loader;
    }
    public function applyLoader($data)
    {
        return app(Pipeline::class)->send($data)
            ->through($this->pipelineLoader)->thenReturn();
    }
    public function adminUrl()
    {
        return $this->urlAdmin ?? ($this->urlAdmin = config('sokeio.admin_url'));
    }
    public function getPath()
    {
        return $this->pathPlatform ?? ($this->pathPlatform = base_path('platform'));
    }
    public function isVendor($path)
    {
        return !str(realpath($path))->startsWith($this->getPath());
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
        $this->theme()->loadFromPath($path);
        if ($item = $this->theme()->addFromPath($path)) {
            return $item->setPackage($servicePackage);
        }
        if ($item = $this->module()->addFromPath($path)) {
            return $item->setPackage($servicePackage);
        }
        return null;
    }
    public function isUrlAdmin()
    {
        return request()->segment(1) === $this->adminUrl() ? true : false;
    }
}
