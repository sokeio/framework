<?php

namespace Sokeio\Platform;

use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Sokeio\Platform\Loader\PageLoader;
use Illuminate\Support\Str;
use Livewire\Component;
use ReflectionClass;
use Sokeio\ILoader;
use Sokeio\LivewireLoader;
use Symfony\Component\Finder\SplFileInfo;

class PlatformManager
{
    private $pipelineLoader = [
        PageLoader::class
    ];
    private $callbackBooting = [];
    private $callbackBooted = [];
    private $urlAdmin;
    private $version;
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
    public function version()
    {
        return $this->version ?? ($this->version = config('sokeio.version'));
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
    public function scanAllClass($directory, $namespace, callable $callback = null, callable $filter = null)
    {
        if (!is_dir($directory)) {
            return [];
        }
        $classList = collect(File::allFiles($directory))
            ->map(function (SplFileInfo $file) use ($namespace) {
                return (string) Str::of($namespace)
                    ->append('\\', $file->getRelativePathname())
                    ->replace(['/', '.php'], ['\\', '']);
            })
            ->filter(function (string $class) {
                return class_exists($class);
            });

        if ($callback) {
            if ($filter) {
                $classList = $classList->filter($filter);
            }
            $classList->each($callback);
        } else {
            return $classList->all();
        }
    }
    public function runLoader(ItemInfo $item, $path, $namespace, $aliasPrefix = '')
    {
        $this->scanAllClass($path, $namespace, function ($class) use ($item, $namespace, $aliasPrefix) {
            $refClass = new ReflectionClass($class);
            if (!$refClass || $refClass->isAbstract()) {
                return;
            }
            if ($refClass->isSubclassOf(Component::class)) {
                LivewireLoader::register($class, $namespace, $aliasPrefix);
            }
            if ($refClass->implementsInterface(ILoader::class)) {
                call_user_func([$class, 'runLoad'], $item);
            }
        });
    }
}
