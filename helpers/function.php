<?php

use Illuminate\Routing\RouteAction;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route as FacadesRoute;
use Sokeio\Facades\Module;
use Sokeio\Facades\Platform;
use Sokeio\Facades\Plugin;
use Sokeio\Facades\Shortcode;
use Sokeio\Facades\Theme;
use Sokeio\Item;
use Sokeio\Models\Setting;

if (!function_exists('byte_encode')) {
    function byte_encode($data)
    {
        return base64_encode(urlencode(json_encode($data ?? [])));
    }
}
if (!function_exists('byte_decode')) {
    function byte_decode($data)
    {
        return json_decode(urldecode(base64_decode($data)), true);
    }
}
if (!function_exists('byte_component')) {
    function byte_component($component, $params = [], $type = '')
    {
        return byte_encode([
            'component' => $component,
            'params' => $params,
            'type' => $type,
            'is_admin' => byte_is_admin()
        ]);
    }
}
if (!function_exists('byte_view')) {
    function byte_view($view, $params = [], $type = '')
    {
        return byte_encode([
            'view' => $view,
            'params' => $params,
            'type' => $type,
            'is_admin' => byte_is_admin()
        ]);
    }
}
if (!function_exists('byte_action')) {
    function byte_action($action, $params = [], $type = '')
    {
        return byte_encode([
            'action' => $action,
            'params' => $params,
            'type' => $type,
            'is_admin' => byte_is_admin()
        ]);
    }
}
if (!function_exists('byte_mode_dev')) {
    function byte_mode_dev()
    {
        return env('SOKEIO_MODE_DEV', false);
    }
}

if (!function_exists('platform_by')) {
    function platform_by($type)
    {
        if ($type == 'module')
            return Module::getFacadeRoot();
        if ($type == 'plugin')
            return Plugin::getFacadeRoot();
        if ($type == 'theme')
            return Theme::getFacadeRoot();
    }
}

if (!function_exists('byte_flags')) {
    function byte_flags($flg, $size = '4x3')
    {
        $fileFlag = __DIR__ . '/../resources/flags/' . $size . '/' . $flg . '.svg';
        if (File::exists($fileFlag)) return file_get_contents($fileFlag);
        return '';
    }
}
if (!function_exists('byte_is_admin')) {
    function byte_is_admin()
    {
        $is_admin = false;
        $arrMiddleware = [];
        if (Request()->route()?->gatherMiddleware()) {
            $arrMiddleware = Request()->route()->gatherMiddleware();
            $is_admin = in_array(\Sokeio\Middleware\ThemeAdmin::class,  $arrMiddleware);
        }
        $url_admin = adminUrl();
        if (request()->segment(1) === $url_admin || $url_admin === '') {
            $is_admin = true;
        }
        if (is_livewire_reuqest() && isset(request()->get('components')[0]['snapshot'])) {
            $snapshot = request()->get('components')[0]['snapshot'];
            if ($snapshot && $snapshot = json_decode($snapshot, true)) {
                if (isset($snapshot['data']['___theme___admin']) && $snapshot['data']['___theme___admin'] == true) {
                    $is_admin = true;
                }
            }
        }
        if (request('___theme___admin') == true) {
            $is_admin = true;
        }
        return apply_filters(PLATFORM_IS_ADMIN, $is_admin);
    }
}

if (!function_exists('is_livewire_reuqest')) {
    function is_livewire_reuqest()
    {
        return class_exists('Livewire\\LivewireManager') && app('Livewire\\LivewireManager')->isLivewireRequest();
    }
}

if (!function_exists('theme_layout')) {
    /**
     * @param  string 
     */
    function theme_layout()
    {

        return Theme::Layout(apply_filters(PLATFORM_THEME_LAYOUT, ''));
    }
}

if (!function_exists('theme_position')) {
    /**
     * @param  string 
     */
    function theme_position($position, array  $args = [])
    {
        ob_start();
        Theme::fire($position, $args);
        return ob_get_clean();
    }
}
if (!function_exists('theme_position_add')) {
    /**
     * @param  string 
     */
    function theme_position_add($position, $callback)
    {
        Theme::addListener($position, $callback);
    }
}
if (!function_exists('theme_class')) {
    /**
     * @param  string 
     */
    function theme_class($default = 'sokeio::page')
    {
        return apply_filters(PLATFORM_THEME_CLASS, $default);
    }
}
if (!function_exists('path_by')) {
    /**
     * @param  string 
     */
    function path_by($name, $path = '')
    {
        return base_path(config('sokeio.appdir.root') . '/' . config('sokeio.appdir.' . $name) . '/' . $path);
    }
}

if (!function_exists('run_cmd')) {
    /**
     * @param  string 
     */
    function run_cmd($path, $cmd)
    {
        chdir($path);
        passthru($cmd);
    }
}

if (!function_exists('callAfterResolving')) {
    function callAfterResolving($name, $callback, $app = null)
    {
        if (!$app) $app = app();
        $app->afterResolving($name, $callback);

        if ($app->resolved($name)) {
            $callback($app->make($name), $app);
        }
    }
}

if (!function_exists('loadViewsFrom')) {
    function loadViewsFrom($path, $namespace = 'sokeio', $app = null)
    {
        callAfterResolving('view', function ($view, $app) use ($path, $namespace) {
            if (
                isset($app->config['view']['paths']) &&
                is_array($app->config['view']['paths'])
            ) {
                foreach ($app->config['view']['paths'] as $viewPath) {
                    if (is_dir($appPath = $viewPath . '/vendor/' . $namespace)) {
                        $view->addNamespace($namespace, $appPath);
                    }
                }
            }
            $view->addNamespace($namespace, $path);
        }, $app);
    }
}

if (!function_exists('AllFile')) {
    function AllFile($directory, $callback = null, $filter = null)
    {
        if (!File::isDirectory($directory)) {
            return [];
        }
        if ($callback) {
            if ($filter) {
                collect(File::allFiles($directory))->filter($filter)->each($callback);
            } else {
                collect(File::allFiles($directory))->each($callback);
            }
        } else {
            return File::allFiles($directory);
        }
    }
}


if (!function_exists('AllClass')) {
    function AllClass($directory, $namespace, $callback = null, $filter = null)
    {
        $files = AllFile($directory);
        if (!$files || !is_array($files)) return [];

        $classList = collect($files)->map(function (SplFileInfo $file) use ($namespace) {
            return (string) Str::of($namespace)
                ->append('\\', $file->getRelativePathname())
                ->replace(['/', '.php'], ['\\', '']);
        });
        if ($callback) {
            if ($filter) {
                $classList = $classList->filter($filter);
            }
            $classList->each($callback);
        } else {
            return $classList;
        }
    }
}

if (!function_exists('AllDirectory')) {
    function AllDirectory($directory, $callback = null, $filter = null)
    {
        if (!File::isDirectory($directory)) {
            return [];
        }
        if ($callback) {
            if ($filter) {
                collect(File::directories($directory))->filter($filter)->each($callback);
            } else {
                collect(File::directories($directory))->each($callback);
            }
        } else {
            return File::directories($directory);
        }
    }
}
if (!function_exists('checkPermission')) {
    function checkPermission($per = '')
    {
        $flg = true;
        if ($per) {
            if (!Platform::CheckGate($per)) $flg = false;
        }
        return apply_filters(PLATFORM_CHECK_PERMISSION,  $flg, $per);
    }
}

if (!function_exists('checkRole')) {
    function checkRole($per = '')
    {
        $flg = true;
        if ($per) {
            /**
             * @var \Sokeio\Models\User $user The class instance.
             */
            $user =  auth()->user();
            if (!$user->isSuperAdmin() && $user->hasRole($per)) $flg = false;
        }
        return apply_filters(PLATFORM_CHECK_ROLE, $flg, $per);
    }
}
if (!function_exists('adminUrl')) {
    function adminUrl()
    {
        return apply_filters(SOKEIO_URL_ADMIN, env('SOKEIO_URL_ADMIN', ''));
    }
}
if (!function_exists('page_title')) {
    function page_title($title = '', $lock = false)
    {
        if ($title) {
            Theme::setTitle($title, $lock = false);
            return;
        }
        return  apply_filters(PLATFORM_PAGE_TITLE, Theme::getTitle());
    }
}


if (!function_exists('set_setting')) {
    function set_setting($key, $value = null, $locked = null)
    {
        try {
            Cache::forget($key);
            $setting = Setting::where('key', $key)->first();
            if ($value !== null) {
                $setting = $setting ?? new Setting(['key' => $key]);
                $setting->value = $value;
                $setting->locked = $locked === true;
                $setting->save();
                Cache::forever($key, $setting->value);
            } else if ($setting != null) {
                $setting->delete();
            }
        } catch (\Exception $e) {
            Log::info($e);
        }
    }
}
if (!function_exists('setting')) {
    /**
     * Get Value: setting("seo_key")
     * Get Value Or Default: setting("seo_key","value_default")
     */
    function setting($key, $default = null)
    {
        try {
            if (Cache::has($key) && Cache::get($key) != '') return Cache::get($key);
            $setting = Setting::where('key', trim($key))->first();
            if ($setting == null) {
                return $default;
            }
            //Set Cache Forever
            Cache::forever($key, $setting->value);
            return $setting->value ?? $default;
        } catch (\Exception $e) {
            if ($default)
                Cache::forever($key, $default);

            return $default;
        }
    }
}


if (!function_exists('route_theme')) {
    function route_theme($_action, $params = [])
    {
        return function () use ($_action, $params) {
            $route = FacadesRoute::current();
            foreach ($params as $key => $value) {
                $route->setParameter($key, $value);
            }
            $route->setAction(array_merge($route->getAction(), RouteAction::parse($route->uri(), ['uses' => $_action])));
            return $route->run();
        };
    }
}
if (!function_exists('BladeToHtml')) {
    function BladeToHtml($content = '')
    {
        return Blade::render($content);
    }
}
if (!function_exists('character_limiter')) {
    /*
    *
    * @access   public
    * @param    string
    * @param    integer
    * @param    string  the end character. Usually an ellipsis
    * @return   string
    */

    function character_limiter($str, $n = 500, $end_char = '&#8230;')
    {
        if (strlen($str) < $n) {
            return $str;
        }

        $str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

        if (strlen($str) <= $n) {
            return $str;
        }

        $out = "";
        foreach (explode(' ', trim($str)) as $val) {
            $out .= $val . ' ';

            if (strlen($out) >= $n) {
                $out = trim($out);
                return (strlen($out) == strlen($str)) ? $out : $out . $end_char;
            }
        }
    }
}



if (!function_exists('byte_path')) {
    function byte_path($type = '', $path = '')
    {
        $pathRoot = config('sokeio.appdir.root', 'sokeio');
        $pathType = config('sokeio.appdir.' . $type, $type . 's');
        return ($pathRoot . '/' . $pathType . ($path ? ('/' . $path) : ''));
    }
}


if (!function_exists('column_size')) {
    function column_size($size = 'col')
    {
        return Item::getSize($size);
    }
}

if (!function_exists('shortcode_render')) {
    function shortcode_render($text)
    {
        return Shortcode::compileOnly($text);
    }
}
