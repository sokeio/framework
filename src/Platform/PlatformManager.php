<?php

namespace Sokeio\Platform;

use Sokeio\Platform\DataInfo;
use Sokeio\Events\NotificationAdd;
use Illuminate\Support\Facades\File;
use Sokeio\Laravel\JsonData;
use Sokeio\Facades\Module;
use Sokeio\Facades\Plugin;
use Sokeio\Facades\Theme;
use Sokeio\Models\Notification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PlatformManager
{
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
    public function listExtend()
    {
        return config('sokeio.extends', ['theme', 'plugin', 'module']);
    }
    
    public function registerComposer($path, $resgister = false)
    {
        if (!file_exists($path . '/composer.json')) return [];

        $composer = JsonData::getJsonFromFile($path . '/composer.json');
        $loader = new \Composer\Autoload\ClassLoader();
        $psr4 = JsonData::getValueByKey($composer, 'autoload.psr-4', []);
        foreach ($psr4 as $key => $value) {
            if (file_exists($path . '/' . $value))
                $loader->addPsr4($key, $path . '/' . $value, true);
        }
        $files = JsonData::getValueByKey($composer, 'autoload.files', []);
        foreach ($files as $file) {
            if (file_exists($path . '/' . $file)) {
                require_once $path . '/' . $file;
                // $loader->add($path,  $file, true);
            }
        }
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
        foreach ($this->listExtend() as $base_type) {
            $links = array_merge($links, platform_by($base_type)->getLinks());
        }
        return $links;
    }
    public function makeLink($relative = false, $force = true)
    {
        foreach (['theme', 'plugin', 'module'] as $item) {
            $pathType = platform_path($item);
            $public = public_path($pathType);
            $appdir = base_path($pathType);
            app('files')->deleteDirectory($public);
            app('files')->ensureDirectoryExists($public);
            app('files')->ensureDirectoryExists($appdir);
        }
        foreach ($this->getLinks() as  $item) {
            foreach ($item as $link => $target) {
                if ($temp = realpath($link))
                    $link = $temp;
                Log::info("------------------start----------------");
                Log::info($link);
                Log::info($target);
                Log::info("----------------------------------");
                if (file_exists($link)) {
                    if (is_link($target)) {
                        app('files')->delete($target);
                    }
                    if (env('SOKEIO_PUBLIC_COPY')) {
                        File::copyDirectory($link, $target);
                    } else {
                        if ($relative) {
                            app('files')->relativeLink($link, $target);
                        } else {
                            app('files')->link($link, $target);
                        }
                    }
                }
            }
        }
        Artisan::call('storage:link');
    }
    public function checkFolderPlatform()
    {
        foreach (['theme', 'plugin', 'module'] as $item) {
            if (!file_exists(platform_path($item))) return false;
        }
        return true;
    }
    public function getDataInfo($path, $register = true)
    {
        foreach ($this->listExtend() as $baseType) {
            if (file_exists(($path . '/' . $baseType . '.json'))) {
                $data_info = JsonData::getJsonFromFile($path . '/' . $baseType . '.json');
                if ($register && isset($data_info['id']) && !(platform_by($baseType)->has($data_info['id']))) {
                    platform_by($baseType)->AddItem($path, DataInfo::checkPathVendor($path, $baseType));
                }
                return [
                    'base_type' => $baseType,
                    'data_info' => $data_info,
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
    public function NotificationAdd($title, $description, $meta_data = [], $to_role = null, $to_user = null)
    {
        $noti = new Notification();
        $noti->title = $title;
        $noti->description = $description;
        $noti->meta_data = $meta_data;
        $noti->to_role = $to_role;
        $noti->to_user = $to_user;
        $noti->save();
        NotificationAdd::dispatch($noti);
        NotificationAdd::broadcast($noti);
    }
    public function CheckConnectDB()
    {
        try {
            DB::connection()->getPdo();
            if (DB::connection()->getDatabaseName() && Schema::hasTable('permissions')) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
    private $gateIgnores = [];
    public function CheckGate()
    {
        $numArgs = func_get_args();
        if (count($numArgs) < 1) return false;
        return auth()->check() && Gate::check($numArgs[0], array_shift($numArgs));
    }
    public function BootGate()
    {
        if (!$this->CheckConnectDB()) return;
        $this->gateIgnores = apply_filters(PLATFORM_PERMISSION_IGNORE, []);
        Gate::before(function ($user, $ability) {
            if (!$user) $user = auth()->user();
            if (!$user) return false;
            if ($user->isBlock()) return false;
            if ($user->isSuperAdmin()) {
                return true;
            }
        });
        app(config('sokeio.model.permission', \Sokeio\Models\Permission::class))->get()->map(function ($permission) {
            Gate::define($permission->slug, function ($user = null) use ($permission) {
                if (!$user) $user = auth()->user();
                if (!$user) return false;
                if (!apply_filters(PLATFORM_CHECK_PERMISSION, true,  $permission, $user)) return false;
                return $user->hasPermissionTo($permission);
            });
        });
        foreach ($this->gateIgnores as $item) {
            Gate::define($item, function () {
                return true;
            });
        }
    }
    private $readyCallback = [];
    public function Ready($callback = null)
    {
        if ($callback && is_callable($callback))
            $this->readyCallback[] = $callback;
    }
    public function DoReady()
    {
        foreach ($this->readyCallback as  $callback) {
            $callback();
        }
    }
    public function setEnv($arrs)
    {

        $path = base_path('.env');

        if (File::exists($path)) {

            $envContent = file_get_contents($path, true);
            foreach ($arrs as $key => $value)
                $envContent = preg_replace('/^' . $key . '=.*$/m', $key . '=\'' . $value . '\'', $envContent);
            file_put_contents($path, $envContent, LOCK_EX);
        }
    }
}
