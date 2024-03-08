<?php

namespace Sokeio\Platform;

use Illuminate\Support\Facades\File;
use Sokeio\Laravel\JsonData;
use Sokeio\Facades\Module;
use Sokeio\Facades\Plugin;
use Sokeio\Facades\Theme;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Sokeio\Platform\Concerns\WithPlatformCallback;
use Sokeio\Platform\Concerns\WithPlatformTime;

class PlatformManager
{
    use WithPlatformTime, WithPlatformCallback;
    private $cachePage = false;
    public function enableCachePage()
    {
        $this->cachePage = true;
    }
    public function disableCachePage()
    {
        $this->cachePage = false;
    }
    public function checkCachePage()
    {
        return $this->cachePage && !env('APP_DEBUG', false) && !Auth::check();
    }
    public function getExtends()
    {
        return config('sokeio.extends', ['theme', 'plugin', 'module']);
    }

    public function registerComposer($path, $resgister = false)
    {
        if (!file_exists($path . '/composer.json')) {
            return [];
        }

        $composer = JsonData::getJsonFromFile($path . '/composer.json');
        $loader = new \Composer\Autoload\ClassLoader();
        $psr4 = JsonData::getValueByKey($composer, 'autoload.psr-4', []);
        foreach ($psr4 as $key => $value) {
            if (file_exists($path . '/' . $value)) {
                $loader->addPsr4($key, $path . '/' . $value, true);
            }
        }
        $files = collect(JsonData::getValueByKey($composer, 'autoload.files', []))
            ->map(function ($item) use ($path) {
                return $path . '/' . $item;
            })->filter(function ($item) {
                return file_exists($item);
            })->toArray();
        $loader->add('files',  $files, true);
        $loader->register(true);
        $providers = JsonData::getValueByKey($composer, 'extra.laravel.providers', []);
        if ($resgister) {
            return collect($providers)->map(function ($item) {
                return app()->register($item);
            });
        }
        return $composer;
    }
    private $arrLink = [];
    public function addLink($source, $target)
    {
        $this->arrLink[$source . $target] = [
            $source => $target
        ];
    }
    public function getLinks()
    {
        $links = $this->arrLink;
        foreach ($this->getExtends() as $baseType) {
            $links = array_merge($links, platformBy($baseType)->getLinks());
        }
        return $links;
    }
    private function makeFile($link, $target, $isRelative = false)
    {
        $resolvedLink = realpath($link);
        if ($resolvedLink) {
            $link = $resolvedLink;
        }
        if (file_exists($link)) {
            if (is_link($target)) {
                app('files')->delete($target);
            }
            if (env('SOKEIO_PUBLIC_COPY')) {
                File::copyDirectory($link, $target);
            } else {
                if ($isRelative) {
                    app('files')->relativeLink($link, $target);
                } else {
                    app('files')->link($link, $target);
                }
            }
        }
    }
    public function makeLink($isRelative = false)
    {
        foreach ($this->getExtends() as $extend) {
            $path = platformPath($extend);
            $publicPath = public_path($path);
            $appPath = base_path($path);
            app('files')->deleteDirectory($publicPath);
            app('files')->ensureDirectoryExists($publicPath);
            app('files')->ensureDirectoryExists($appPath);
        }
        foreach ($this->getLinks() as  $links) {
            foreach ($links as $link => $target) {
                $this->makeFile($link, $target, $isRelative);
            }
        }
        Artisan::call('storage:link');
    }
    public function checkFolderPlatform()
    {
        foreach ($this->getExtends() as $item) {
            if (!file_exists(platformPath($item))) {
                return false;
            }
        }
        return true;
    }
    public function getDataInfo($path, $register = true)
    {
        foreach ($this->getExtends() as $baseType) {
            if (file_exists(($path . '/' . $baseType . '.json'))) {
                $dataInfo = JsonData::getJsonFromFile($path . '/' . $baseType . '.json');
                if ($register && isset($dataInfo['id']) && !(platformBy($baseType)->has($dataInfo['id']))) {
                    platformBy($baseType)->addItem($path, checkPathVendor($path, $baseType));
                }
                return [
                    'baseType' => $baseType,
                    'dataInfo' => $dataInfo,
                ];
            }
        }
        return null;
    }
    public function getModels()
    {
        $arr = [];
        $arr = [...$arr, ...Module::getModels()];
        $arr = [...$arr, ...Theme::getModels()];
        $arr = [...$arr, ...Plugin::getModels()];
        return apply_filters(PLATFORM_MODEL_LIST, $arr);
    }

    public function checkConnectDB()
    {
        try {
            DB::connection()->getPdo();
            if (DB::connection()->getDatabaseName() && Schema::hasTable('permissions')) {
                return true;
            }
        } catch (\Exception $e) {
            Log::log('error', $e->getMessage());
        }
        return false;
    }
    private $gateIgnores = [];
    public function checkGate()
    {
        $numArgs = func_get_args();
        if (empty($numArgs)) {
            return false;
        }
        return auth()->check() && Gate::check($numArgs[0], array_shift($numArgs));
    }
    public function bootGate()
    {
        if (!$this->checkConnectDB()) {
            return;
        }

        $this->registerGlobalGates();
        $this->registerPermissionGates();
    }

    private function registerGlobalGates()
    {
        Gate::before(function ($user, $ability) {
            if (!$user) {
                $user = auth()->user();
            }
            if (!$user || $user->isBlock()) {
                return false;
            }
            if (in_array($ability, $this->gateIgnores)) {
                return true;
            }
            return $user->isSuperAdmin();
        });
    }

    private function registerPermissionGates()
    {
        $permissions = app(config('sokeio.model.permission', \Sokeio\Models\Permission::class))->get();

        $permissions->map(function ($permission) {
            Gate::define($permission->slug, function ($user = null) use ($permission) {
                if (!$user) {
                    $user = auth()->user();
                }
                if (!$user || !apply_filters(PLATFORM_CHECK_PERMISSION, true,  $permission, $user)) {
                    return false;
                }
                return $user->hasPermissionTo($permission);
            });
        });
    }
    public function setEnv($arrs)
    {
        $path = base_path('.env');
        if (File::exists($path)) {

            $envContent = file_get_contents($path, true);
            foreach ($arrs as $key => $value) {
                $envContent = preg_replace('/^' . $key . '=.*$/m', $key . '=\'' . $value . '\'', $envContent);
            }
            file_put_contents($path, $envContent, LOCK_EX);
        }
    }
}
