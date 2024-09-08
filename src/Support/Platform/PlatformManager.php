<?php

namespace Sokeio\Support\Platform;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Illuminate\Support\Str;
use Livewire\Component;
use ReflectionClass;
use Sokeio\ILoader;
use Sokeio\Support\Platform\Concerns\{
    WithLivewireComponent,
    WithPipelineLoader,
    WithPlatform
};
use Symfony\Component\Finder\SplFileInfo;

class PlatformManager
{
    use WithLivewireComponent, WithPipelineLoader, WithPlatform;

    public function isVendor($path)
    {
        return !str(realpath($path))->startsWith($this->getPlatformPath());
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
                $this->registerLivewire($class, $namespace, $aliasPrefix);
            }
            if ($refClass->implementsInterface(ILoader::class)) {
                call_user_func([$class, 'runLoad'], $item);
            }
        });
    }
}
