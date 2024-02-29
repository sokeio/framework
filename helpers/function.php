<?php

use Illuminate\Routing\RouteAction;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Js;
use Livewire\Livewire;
use Symfony\Component\Finder\SplFileInfo;

use Sokeio\Breadcrumb;
use Sokeio\Facades\Menu;
use Sokeio\Facades\Module;
use Sokeio\Facades\Platform;
use Sokeio\Facades\Plugin;
use Sokeio\Facades\Theme;
use Sokeio\Facades\ThemeOption;
use Sokeio\Facades\Shortcode;
use Sokeio\Icon\IconManager;
use Sokeio\Menu\MenuBuilder;
use Sokeio\Models\MenuLocation;
use Sokeio\Models\Setting;
use Sokeio\Notification;
use Sokeio\Platform\PermalinkManager;
use Sokeio\Platform\ThemeOptionManager;

if (!function_exists('sokeio_encode')) {
    function sokeio_encode($data)
    {
        return base64_encode(urlencode(json_encode($data ?? [])));
    }
}
if (!function_exists('sokeio_decode')) {
    function sokeio_decode($data)
    {
        return json_decode(urldecode(base64_decode($data)), true);
    }
}
if (!function_exists('sokeio_time')) {
    function sokeio_time()
    {
        return Platform::ExecutionTime();
    }
}

if (!function_exists('sokeio_version')) {
    function sokeio_version()
    {
        return Module::find('sokeio')?->getVersion();
    }
}
if (!function_exists('sokeio_component')) {
    function sokeio_component($component, $params = [], $type = '')
    {
        return sokeio_encode([
            'component' => $component,
            'params' => $params,
            'type' => $type,
            'is_admin' => sokeio_is_admin()
        ]);
    }
}
if (!function_exists('sokeio_view')) {
    function sokeio_view($view, $params = [], $type = '')
    {
        return sokeio_encode([
            'view' => $view,
            'params' => $params,
            'type' => $type,
            'is_admin' => sokeio_is_admin()
        ]);
    }
}
if (!function_exists('sokeio_action')) {
    function sokeio_action($action, $params = [], $type = '')
    {
        return sokeio_encode([
            'action' => $action,
            'params' => $params,
            'type' => $type,
            'is_admin' => sokeio_is_admin()
        ]);
    }
}
if (!function_exists('sokeio_icons')) {
    function sokeio_icons()
    {
        return IconManager::getInstance()->toArray();
    }
}
if (!function_exists('sokeio_js')) {
    function sokeio_js($data)
    {
        return Js::from($data);
    }
}

if (!function_exists('sokeio_mode_dev')) {
    function sokeio_mode_dev()
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

if (!function_exists('route_crud')) {
    function route_crud($name, $table, $form, $isGet = false)
    {
        Route::get($name, $table)->name($name);
        if ($isGet) {
            Route::get($name . '/new', $form)->name($name . '.add');
            Route::get($name . '/edit/{dataId}', $form)->name($name . '.edit');
        } else {
            Route::post($name . '/new', $form)->name($name . '.add');
            Route::post($name . '/edit/{dataId}', $form)->name($name . '.edit');
        }
    }
}
if (!function_exists('sokeio_flags')) {
    function sokeio_flags($flg, $size = '4x3')
    {
        $fileFlag = __DIR__ . '/../resources/flags/' . $size . '/' . $flg . '.svg';
        if (File::exists($fileFlag)) return file_get_contents($fileFlag);
        return '';
    }
}
if (!function_exists('sokeio_is_admin')) {
    function sokeio_is_admin()
    {
        $is_admin = false;
        $arrMiddleware = [];
        if (Request()->route()?->gatherMiddleware()) {
            $arrMiddleware = Request()->route()->gatherMiddleware();
            $is_admin = in_array(\Sokeio\Middleware\ThemeAdmin::class,  $arrMiddleware);
        }
        $url_admin = admin_url();
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
        return apply_filters(SOKEIO_IS_ADMIN, $is_admin);
    }
}

if (!function_exists('is_livewire_reuqest')) {
    function is_livewire_reuqest()
    {
        return class_exists('Livewire\\LivewireManager') && app('Livewire\\LivewireManager')->isLivewireRequest();
    }
}
if (!function_exists('is_livewire_reuqest_updated')) {
    function is_livewire_reuqest_updated()
    {
        $updated = request('components.0.updates');
        return is_livewire_reuqest() && isset($updated) && is_array($updated) && count($updated) > 0;
    }
}

if (!function_exists('livewire_render')) {
    function livewire_render($name, $params = [])
    {
        return Livewire::mount($name, $params);
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

if (!function_exists('theme_option')) {
    /**
     * @return  string | ThemeOptionManager| null
     */
    function theme_option($key = '', $default = null): string | ThemeOptionManager| null
    {
        return $key ? ThemeOption::getValue($key, $default) : ThemeOption::getFacadeRoot();
    }
}


if (!function_exists('theme_position')) {
    /**
     * @param  string 
     */
    function theme_position($position, array  $args = [])
    {
        ob_start();
        echo theme_menu($position);
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
if (!function_exists('admin_url')) {
    function admin_url()
    {
        return apply_filters(SOKEIO_URL_ADMIN, env('SOKEIO_URL_ADMIN', ''));
    }
}
if (!function_exists('breadcrumb')) {
    function breadcrumb($key = ''): Breadcrumb
    {
        return apply_filters(SOKEIO_BREADCRUMD, Breadcrumb::Make($key));
    }
}

if (!function_exists('notification')) {
    function notification(): Notification
    {
        return Notification::Make();
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

if (!function_exists('route_filter')) {
    function route_filter($_key, $default = [])
    {
        return function () use ($_key, $default) {
            $route = Route::current();
            $_params = [];
            $_action =  apply_filters($_key, $default);
            if ($_action == null)
                return $route->run();
            if (is_array($_action)) {
                ['uses' => $action, 'params' => $_params] = $_action;
                if ($action) {
                    $_action = $action;
                }
            }

            if ($_params) {
                foreach ($_params as $key => $value) {
                    $route->setParameter($key, $value);
                }
            }
            if (is_string($_action) && !class_exists($_action)) {
                $_action = function () use ($_action) {
                    return view_scope($_action);
                };
            }
            $route->setAction(array_merge($route->getAction(), RouteAction::parse($route->uri(), ['uses' => $_action])));
            return $route->run();
        };
    }
}

if (!function_exists('route_theme')) {
    function route_theme($_action, $params = [])
    {
        return function () use ($_action, $params) {
            $route = Route::current();
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



if (!function_exists('platform_path')) {
    function platform_path($type = '', $path = '')
    {
        $pathRoot = config('sokeio.appdir.root', 'platform');
        $pathType = config('sokeio.appdir.' . $type, $type . 's');
        return ($pathRoot . '/' . $pathType . ($path ? ('/' . $path) : ''));
    }
}



if (!function_exists('menu_admin')) {
    function menu_admin($render = false): MenuBuilder| string
    {
        if ($render) return Menu::render('menu_admin_sidebar');
        return  Menu::position('menu_admin_sidebar');
    }
}
if (!function_exists('theme_menu')) {
    function theme_menu($name)
    {
        $location =  MenuLocation::whereJsonContains('locations', $name)->with('menus')->first();;
        if ($location == null || $location->menus == null) return '';
        $menuSorted = $location->menus;
        return Menu::position($name)->withDatabase($menuSorted)->toHtml();
    }
}

if (!function_exists('permalink_route')) {
    function permalink_route($key, $permalink, $route_class, $route_name)
    {
        PermalinkManager::Route($key, $permalink, $route_class, $route_name);
    }
}

if (!function_exists('column_size')) {
    function column_size($size = 'col')
    {
        switch ($size) {
            case "col1":
                return "col-xs-12 col-sm-12 col-md-1 col-lg-1";
            case "col2":
                return "col-xs-12 col-sm-12 col-md-1 col-lg-2";
            case "col3":
                return "col-xs-12 col-sm-12 col-md-4 col-lg-3";
            case "col4":
                return "col-xs-12 col-sm-12 col-md-4 col-lg-4";
            case "col5":
                return "col-xs-12 col-sm-12 col-md-4 col-lg-5";
            case "col6":
                return "col-xs-12 col-sm-12 col-md-4 col-lg-6";
            case "col7":
                return "col-xs-12 col-sm-12 col-md-8 col-lg-7";
            case "col8":
                return "col-xs-12 col-sm-12 col-md-8 col-lg-8";
            case "col9":
                return "col-xs-12 col-sm-12 col-md-8 col-lg-9";
            case "col10":
                return "col-xs-12 col-sm-12 col-md-12 col-lg-10";
            case "col11":
                return "col-xs-12 col-sm-12 col-md-12 col-lg-11";
            case "col12":
                return "col-xs-12 col-sm-12 col-md-12 col-lg-12";
            default:
                return "col";
        }
    }
}



if (!function_exists('shortcode_render')) {
    function shortcode_render($text)
    {
        return Shortcode::compile($text);
    }
}
if (function_exists('module_active')) {
    if (!function_exists('module_active')) {
        function module_active($name)
        {
            return Module::find($name)->isActive();
        }
    }
}

if (function_exists('plugin_active')) {
    if (!function_exists('plugin_active')) {
        function plugin_active($name)
        {
            return Plugin::find($name)->isActive();
        }
    }
}

if (function_exists('theme_active')) {
    if (!function_exists('theme_active')) {
        function theme_active($name)
        {
            return Theme::find($name)->isActive();
        }
    }
}
