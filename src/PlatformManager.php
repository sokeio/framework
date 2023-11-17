<?php

namespace BytePlatform;

use BytePlatform\DataInfo;
use BytePlatform\Events\NotificationAdd;
use Illuminate\Support\Facades\File;
use BytePlatform\Laravel\JsonData;
use BytePlatform\Facades\Module;
use BytePlatform\Facades\Plugin;
use BytePlatform\Facades\Theme;
use BytePlatform\Models\Notification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        return config('byte.extends', ['theme', 'plugin', 'module']);
    }
    private $byteplatformFileVersion;
    public function FileVersion()
    {
        return $this->byteplatformFileVersion ?? ($this->byteplatformFileVersion = json_decode(file_get_contents(config('byte.updator.url')), true));
    }
    public function unzip($file, $extTo)
    {
        $zipHandle = new  \ZipArchive();
        $zipHandle->open($file);
        File::ensureDirectoryExists($extTo);
        $zipHandle->extractTo($extTo);
        $zipHandle->close();
    }
    public function download($remote_file_url, $local_file, $throw = false)
    {
        try {
            $update = file_get_contents($remote_file_url);
            file_put_contents($local_file, $update);
            return true;
        } catch (\Exception $e) {
            if ($throw) throw $e;
            return false;
        }
    }
    public function findFile($id_or_name)
    {
        $fileVersion = $this->FileVersion();
        if (!$fileVersion || !is_array($fileVersion) || !isset($fileVersion['files'])) return null;
        $rs = array_values(array_filter($fileVersion['files'], function ($item) use ($id_or_name) {
            if (isset($item['id']) && $item['id'] === $id_or_name) return true;
            return false;
        }));
        if (count($rs) > 0) return $rs[0];
        $rs = array_values(array_filter($fileVersion['files'], function ($item) use ($id_or_name) {
            if (isset($item['name']) && $item['name'] === $id_or_name) return true;
            return false;
        }));
        if (count($rs) > 0) return $rs[0];
        return null;
    }
    public function downloadFile($id_or_name)
    {
        $json = $this->findFile($id_or_name);
        if ($json && isset($json['download'])) {
            $path = base_path(config('byte.appdir.root') . '/' . config('byte.updator.temps') . '/');
            File::ensureDirectoryExists($path);
            $path = $path . $id_or_name . '-' . time() . '.zip';
            if ($this->download($json['download'], $path)) return $path;
        }
        return null;
    }
    public function install($id_or_name)
    {
        $file = $this->downloadFile($id_or_name);
        if ($file) {
            return  $this->installLocal($file);
        }
        return false;
    }
    private function findFolderRoot($path)
    {
        if (File::exists($path . '/theme.json'))
            return [
                'type' => 'theme',
                'path' => $path,
                'info' => json_decode(file_get_contents($path . '/theme.json'), true)
            ];
        if (File::exists($path . '/plugin.json'))
            return [
                'type' => 'plugin',
                'path' => $path,
                'info' => json_decode(file_get_contents($path . '/plugin.json'), true)
            ];
        if (File::exists($path . '/module.json'))
            return [
                'type' => 'module',
                'path' => $path,
                'info' => json_decode(file_get_contents($path . '/module.json'), true)
            ];
        $array = File::directories($path);
        foreach ($array as $item) {
            $rs = $this->findFolderRoot($item);
            if ($rs != null) {
                return $rs;
            }
        }
        return null;
    }
    public function installLocal($file)
    {
        $path_folder = dirname($file) . '/temp-' . time();
        $this->unzip($file, $path_folder);
        $rs = $this->findFolderRoot($path_folder);
        if ($rs != null) {
            File::copyDirectory($rs['path'], path_by($rs['type'], $rs['info']['name']));
        }
        File::deleteDirectories($path_folder);
        File::deleteDirectories($path_folder);
        File::delete($file);
        return  $rs;
    }
    public function Load($path)
    {
        Theme::Load($path . '/themes');
        Plugin::Load($path . '/plugins');
        Module::Load($path . '/modules');
        //RouteEx::Load($path . '/routes/');
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
            $pathType = byte_path($item);
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
                    if (env('BYTE_PUBLIC_COPY')) {
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
            if (!file_exists(byte_path($item))) return false;
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
            if (DB::connection()->getDatabaseName()) {
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
        return Gate::check($numArgs[0], array_shift($numArgs));
    }
    public function BootGate()
    {
        if(!$this->CheckConnectDB()) return;
        $this->gateIgnores = apply_filters(PLATFORM_PERMISSION_IGNORE, []);
        Gate::before(function ($user, $ability) {
            if (!$user) $user = auth()->user();
            if ($user->isBlock()) return false;
            if ($user->isSuperAdmin()) {
                return true;
            }
        });
        app(config('byte.model.permission', \BytePlatform\Models\Permission::class))->get()->map(function ($permission) {
            Gate::define($permission->slug, function ($user = null) use ($permission) {
                if (!$user) $user = auth()->user();
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
}
