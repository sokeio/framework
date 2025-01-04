<?php

namespace Sokeio\Support\Platform;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Process\PhpExecutableFinder;
use Livewire\Livewire;
use Symfony\Component\Process\Process;
use Illuminate\Support\Str;
use Livewire\Component;
use ReflectionClass;
use Sokeio\ILoader;
use Sokeio\Support\Menu\MenuManager;
use Sokeio\Support\Platform\Concerns\{
    WithLivewireComponent,
    WithPipelineLoader,
    WithPlatform,
    WithPlatformInfo
};
use Symfony\Component\Finder\SplFileInfo;

class PlatformManager
{
    use WithLivewireComponent, WithPipelineLoader, WithPlatform, WithPlatformInfo;
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
        return str($this->currentUrl())->startsWith($this->adminUrl()) || $this->adminUrl() === '/';
    }
    public function gate()
    {
        return GateManager::getInstance();
    }
    public function menu()
    {
        return MenuManager::getInstance('admin');
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
    public function apiOk($data = null, $message = null, $code = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'errors' => [],
            'success' => true,
        ]);
    }
    public function apiError($message = null, $errors = [], $code = 500)
    {
        return response()->json([
            'message' => $message,
            'success' => false,
            'data' => null,
            'errors' => $errors,
            'status_code' => $code
        ], 403);
    }
    public function artisanInBackground($command, $data = null)
    {
        $phpBinaryFinder = new PhpExecutableFinder();

        $phpBinaryPath = $phpBinaryFinder->find();

        $process = new Process([$phpBinaryPath, base_path('artisan'), $command, $data]); // (['php', 'artisan', 'foo:bar', 'json data'])

        $process->setoptions(['create_new_console' => true]); //Run process in background 
        $process->start();
    }
}
