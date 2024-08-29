<?php

namespace Sokeio\Platform;

use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
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
    public function getPlatformPath()
    {
        return $this->pathPlatform ?? ($this->pathPlatform = base_path('platform'));
    }
    public function isVendor($path)
    {
        return !str(realpath($path))->startsWith($this->getPlatformPath());
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
    public function routeWeb($group, $isAuth = false)
    {
        Route::middleware(['sokeio.web' . ($isAuth ? '.auth' : '')])->group($group);
    }
    public function routeAdmin($group, $isGuest = false)
    {
        Route::middleware(['sokeio.admin' . ($isGuest ? '.guest' : '')])
            ->prefix($this->adminUrl())->as('admin.')->group($group);
    }
    public function routeApi($group, $isGuest = false)
    {
        Route::middleware(['sokeio.api' . ($isGuest ? '.guest' : '')])
            ->prefix('api')->as('api.')->group($group);
    }
    public function currentUrl()
    {
        return Livewire::originalPath();
    }
    public function isUrlAdmin()
    {
        return str($this->currentUrl())->startsWith($this->adminUrl());
    }
    public function gate()
    {
        return GateManager::getInstance($this);
    }
}
