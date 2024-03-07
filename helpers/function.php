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

if (!function_exists('sokeioEncode')) {
    function sokeioEncode($data)
    {
        return base64_encode(urlencode(json_encode($data ?? [])));
    }
}
if (!function_exists('sokeioDecode')) {
    function sokeioDecode($data)
    {
        return json_decode(urldecode(base64_decode($data)), true);
    }
}
if (!function_exists('sokeioTime')) {
    function sokeioTime()
    {
        return Platform::ExecutionTime();
    }
}

if (!function_exists('sokeioVersion')) {
    function sokeioVersion()
    {
        return Module::find('sokeio')?->getVersion();
    }
}
if (!function_exists('sokeioComponent')) {
    function sokeioComponent($component, $params = [], $type = '')
    {
        return sokeioEncode([
            'component' => $component,
            'params' => $params,
            'type' => $type,
            'is_admin' => sokeioIsAdmin()
        ]);
    }
}
if (!function_exists('sokeioView')) {
    function sokeioView($view, $params = [], $type = '')
    {
        return sokeioEncode([
            'view' => $view,
            'params' => $params,
            'type' => $type,
            'is_admin' => sokeioIsAdmin()
        ]);
    }
}
if (!function_exists('sokeioAction')) {
    function sokeioAction($action, $params = [], $type = '')
    {
        return sokeioEncode([
            'action' => $action,
            'params' => $params,
            'type' => $type,
            'is_admin' => sokeioIsAdmin()
        ]);
    }
}
if (!function_exists('sokeioIcons')) {
    function sokeioIcons()
    {
        return IconManager::getInstance()->toArray();
    }
}
if (!function_exists('sokeioJS')) {
    function sokeioJS($data)
    {
        return Js::from($data);
    }
}

if (!function_exists('sokeioModeDev')) {
    function sokeioModeDev()
    {
        return env('SOKEIO_MODE_DEV', false);
    }
}

if (!function_exists('platformBy')) {
    function platformBy($type)
    {
        if ($type == 'module') {
            return Module::getFacadeRoot();
        }
        if ($type == 'plugin') {
            return Plugin::getFacadeRoot();
        }
        if ($type == 'theme') {
            return Theme::getFacadeRoot();
        }
    }
}

if (!function_exists('routeCrud')) {
    function routeCrud($name, $table, $form, $isGet = false)
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
if (!function_exists('sokeioFlags')) {
    function sokeioFlags($flg, $size = '4x3')
    {
        $fileFlag = __DIR__ . '/../resources/flags/' . $size . '/' . $flg . '.svg';
        if (File::exists($fileFlag)) {
            return file_get_contents($fileFlag);
        }
        return '';
    }
}
if (!function_exists('sokeioIsAdmin')) {
    function sokeioIsAdmin()
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
        if (isLivewireRequest() && isset(request()->get('components')[0]['snapshot'])) {
            $snapshot = request()->get('components')[0]['snapshot'];
            if (
                $snapshot && $snapshot = json_decode($snapshot, true)
                && isset($snapshot['data']['___theme___admin'])
            ) {
                $is_admin = ($snapshot['data']['___theme___admin'] === true);
            }
        }
        if (request('___theme___admin') === true) {
            $is_admin = true;
        }
        return apply_filters(SOKEIO_IS_ADMIN, $is_admin);
    }
}

if (!function_exists('isLivewireRequest')) {
    function isLivewireRequest()
    {
        return class_exists('Livewire\\LivewireManager') && app('Livewire\\LivewireManager')->isLivewireRequest();
    }
}
if (!function_exists('isLivewireRequestUpdated')) {
    function isLivewireRequestUpdated()
    {
        $updated = request('components.0.updates');
        return isLivewireRequest() && isset($updated) && is_array($updated) && count($updated) > 0;
    }
}

if (!function_exists('livewireRender')) {
    function livewireRender($name, $params = [])
    {
        return Livewire::mount($name, $params);
    }
}

if (!function_exists('themeLayout')) {
    /**
     * @return  string | null
     */
    function themeLayout()
    {
        return Theme::Layout(apply_filters(PLATFORM_THEME_LAYOUT, ''));
    }
}

if (!function_exists('themeOption')) {
    /**
     * @return  string | ThemeOptionManager| null
     */
    function themeOption($key = '', $default = null): string | ThemeOptionManager| null
    {
        return $key ? ThemeOption::getValue($key, $default) : ThemeOption::getFacadeRoot();
    }
}


if (!function_exists('themePosition')) {
    /**
     * @return  string | null
     */
    function themePosition($position, array  $args = [])
    {
        ob_start();
        echo themeMenu($position);
        Theme::fire($position, $args);
        return ob_get_clean();
    }
}
if (!function_exists('themePositionAdd')) {
    /**
     * @param  string $position
     * @param  mixed $callback
     */
    function themePositionAdd($position, $callback)
    {
        Theme::addListener($position, $callback);
    }
}
if (!function_exists('themeClass')) {
    /**
     * @param  string $default
     * @return  string | null
     */
    function themeClass($default = 'sokeio::page')
    {
        return apply_filters(PLATFORM_THEME_CLASS, $default);
    }
}
if (!function_exists('pathBy')) {
    /**
     * @param  string $name
     * @param  string $path
     */
    function pathBy($name, $path = '')
    {
        return base_path(config('sokeio.appdir.root') . '/' . config('sokeio.appdir.' . $name) . '/' . $path);
    }
}

if (!function_exists('runCmd')) {
    /**
     * @param  string $path
     * @param  string $cmd
     */
    function runCmd($path, $cmd)
    {
        chdir($path);
        passthru($cmd);
    }
}

if (!function_exists('callAfterResolving')) {
    function callAfterResolving($name, $callback, $app = null)
    {
        if (!$app) {
            $app = app();
        }
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
            $view->addNamespace($namespace, $path);
            if (isset($app->config['view']['paths'])) {
                foreach ($app->config['view']['paths'] as $viewPath) {
                    if (is_dir($vendorPath = $viewPath . '/vendor')) {
                        $view->addNamespace($namespace, $vendorPath);
                    }
                }
            }
        }, $app);
    }

}

if (!function_exists('getAllFile')) {
    function getAllFile($directory, callable $callback = null, callable $filter = null)
    {
        if (!is_dir($directory)) {
            return [];
        }

        $files = collect(File::allFiles($directory));

        if ($filter) {
            $files = $files->filter($filter);
        }

        if ($callback) {
            $files->each($callback);
        }

        return $files->all();
    }

}


if (!function_exists('getAllClass')) {
    function getAllClass($directory, $namespace, callable $callback = null, callable $filter = null)
    {
        $files = getAllFile($directory);
        if (!$files || !is_array($files)) {
            return [];
        }

        $classList = collect($files)
            ->map(function (SplFileInfo $file) use ($namespace) {
                return $namespace . '\\' . str_replace('/', '\\', $file->getRelativePathname())
                    ->replace('.php', '');
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

}

if (!function_exists('getAllDirectory')) {
    function getAllDirectory($directory, $callback = null, $filter = null)
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
            $flg = Platform::CheckGate($per);
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
            $flg = $user->isSuperAdmin() || $user->hasRole($per);
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
if (!function_exists('breadcrumb')) {
    function breadcrumb($key = ''): Breadcrumb
    {
        return apply_filters(SOKEIO_BREADCRUMD, Breadcrumb::make($key));
    }
}

if (!function_exists('notification')) {
    function notification(): Notification
    {
        return Notification::make();
    }
}
if (!function_exists('setSetting')) {
    function setSetting($key, $value = null, $locked = null)
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
            } elseif ($setting != null) {
                $setting->delete();
            }
        } catch (\Exception $e) {
            Log::info($e);
        }
    }
}
if (!function_exists('setting')) {
    /**
     * @param  string $key
     * @param  string|null $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        $result = $default;
        try {
            if (Cache::has($key) && Cache::get($key) != '') {
                $result = Cache::get($key);
            } else {
                $setting = Setting::where('key', trim($key))->first();
                if ($setting) {
                    $result = $setting->value;
                    Cache::forever($key, $result);
                }
            }
        } catch (\Exception $e) {
            Log::error($e);
        }
        return $result;
    }
}

if (!function_exists('routeFilter')) {
    function routeFilter($_key, $default = [])
    {
        return function () use ($_key, $default) {
            $route = Route::current();
            $_params = [];
            $_action =  apply_filters($_key, $default);
            if ($_action == null) {
                return $route->run();
            }
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
            $action = RouteAction::parse($route->uri(), ['uses' => $_action]);
            $route->setAction($action);
            return $route->run();
        };
    }
}

if (!function_exists('routeTheme')) {
    function routeTheme($_action, $params = [])
    {
        return function () use ($_action, $params) {
            $route = Route::current();
            foreach ($params as $key => $value) {
                $route->setParameter($key, $value);
            }
            $route->setAction(RouteAction::parse($route->uri(), ['uses' => $_action]));
            return $route->run();
        };
    }
}
if (!function_exists('bladeRender')) {
    function bladeRender($content = '')
    {
        return Blade::render($content);
    }
}
if (!function_exists('characterLimiter')) {
    /*
    *
    * @access   public
    * @param    string
    * @param    integer
    * @param    string  the end character. Usually an ellipsis
    * @return   string
    */

    function characterLimiter($str, int $limit = 500, string $endChar = '…'): string
    {
        if (mb_strwidth($str) <= $limit) {
            return $str;
        }

        return rtrim(mb_strimwidth($str, 0, $limit, '', 'UTF-8')) . $endChar;
    }
}



if (!function_exists('platformPath')) {
    function platformPath($type = '', $path = '')
    {
        $pathRoot = config('sokeio.appdir.root', 'platform');
        $pathType = config('sokeio.appdir.' . $type, $type . 's');
        return $pathRoot . '/' . $pathType . ($path ? ('/' . $path) : '');
    }
}



if (!function_exists('menuAdmin')) {
    function menuAdmin($render = false): MenuBuilder| string
    {
        if ($render) {
            return Menu::render('menuAdmin_sidebar');
        }
        return  Menu::position('menuAdmin_sidebar');
    }
}
if (!function_exists('themeMenu')) {
    function themeMenu($name)
    {
        $location =  MenuLocation::whereJsonContains('locations', $name)->with('menus')->first();
        if ($location == null || $location->menus == null) {
            return '';
        }
        $menuSorted = $location->menus;
        return Menu::position($name)->withDatabase($menuSorted)->toHtml();
    }
}

if (!function_exists('permalinkRoute')) {
    function permalinkRoute($key, $permalink, $route_class, $route_name)
    {
        PermalinkManager::Route($key, $permalink, $route_class, $route_name);
    }
}

if (!function_exists('columnSize')) {
    /**
     * Returns the appropriate column size class based on the input size.
     *
     * @param string $size (Optional) The size of the column. Defaults to 'col'.
     * @return string The corresponding column size class.
     */
    function columnSize($size = 'col')
    {
        $columnSizes = [
            "col1" => "col-xs-12 col-sm-12 col-md-1 col-lg-1",
            "col2" => "col-xs-12 col-sm-12 col-md-1 col-lg-2",
            "col3" => "col-xs-12 col-sm-12 col-md-4 col-lg-3",
            "col4" => "col-xs-12 col-sm-12 col-md-4 col-lg-4",
            "col5" => "col-xs-12 col-sm-12 col-md-4 col-lg-5",
            "col6" => "col-xs-12 col-sm-12 col-md-4 col-lg-6",
            "col7" => "col-xs-12 col-sm-12 col-md-8 col-lg-7",
            "col8" => "col-xs-12 col-sm-12 col-md-8 col-lg-8",
            "col9" => "col-xs-12 col-sm-12 col-md-8 col-lg-9",
            "col10" => "col-xs-12 col-sm-12 col-md-12 col-lg-10",
            "col11" => "col-xs-12 col-sm-12 col-md-12 col-lg-11",
            "col12" => "col-xs-12 col-sm-12 col-md-12 col-lg-12",
            "auto" => "col-auto"
        ];
        return $columnSizes[$size] ?? "col";
    }
}



if (!function_exists('shortcodeRender')) {
    function shortcodeRender($text)
    {
        return Shortcode::compile($text);
    }
}
if (!function_exists('moduleIsActive')) {
    function moduleIsActive($name)
    {
        return Module::find($name)?->isActiveOrVendor();
    }
}

if (!function_exists('pluginIsActive')) {
    function pluginIsActive($name)
    {
        return Plugin::find($name)?->isActiveOrVendor();
    }
}
if (!function_exists('themeIsActive')) {
    function themeIsActive($name)
    {
        return Theme::find($name)?->isActiveOrVendor();
    }
}
