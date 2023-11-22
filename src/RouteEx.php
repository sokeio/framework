<?php

namespace Sokeio;

use Illuminate\Support\Facades\Route;

class RouteEx
{
    private static $cacheRouteLoaded = [];
    public static function checkPath($path)
    {
        return (isset(self::$cacheRouteLoaded[$path]) && self::$cacheRouteLoaded[$path]);
    }
    public static function Api($callback)
    {
        Route::middleware(apply_filters(PLATFORM_MIDDLEWARE_API, ['api', \Sokeio\Middleware\Platform::class]))
            ->prefix('api')
            ->group($callback);
    }
    public static function Web($callback)
    {
        Route::middleware(apply_filters(PLATFORM_MIDDLEWARE_WEB, ['web', \Sokeio\Middleware\Platform::class]))
            ->group($callback);
    }
    public static function Admin($callback)
    {
        Route::middleware(apply_filters(PLATFORM_MIDDLEWARE_ADMIN, ['web', \Sokeio\Middleware\Authenticate::class, \Sokeio\Middleware\ThemeAdmin::class, \Sokeio\Middleware\Platform::class]))
            ->prefix(adminUrl())
            ->group($callback);
    }
    private static function LoadRoute($path)
    {
        if (file_exists(($path . 'api.php')) || file_exists(($path . 'api'))) {
            self::Api(function () use ($path) {
                if (file_exists(($path . 'api.php'))) {
                    require_once($path . 'api.php');
                }
                if (file_exists(($path . 'api'))) {
                    AllFile(($path . 'api'), function ($file) {
                        require_once $file;
                    }, null);
                }
            });
        }
        if (file_exists(($path . 'web.php')) || file_exists(($path . 'web'))) {
            self::Web(function () use ($path) {
                if (file_exists(($path . 'web.php'))) {
                    require_once($path . 'web.php');
                }
                if (file_exists(($path . 'web'))) {
                    AllFile(($path . 'web'), function ($file) {
                        require_once $file;
                    }, null);
                }
            });
        }
        if (file_exists(($path . 'admin.php')) || file_exists(($path . 'admin'))) {
            self::Admin(function () use ($path) {
                if (file_exists(($path . 'admin.php'))) {
                    require_once($path . 'admin.php');
                }
                if (file_exists(($path . 'admin'))) {
                    AllFile(($path . 'admin'), function ($file) {
                        require_once $file;
                    }, null);
                }
            });
        }
    }
    public static function Load($path)
    {
        if (self::checkPath($path)) return;
        self::$cacheRouteLoaded[$path] = true;

        if ($subdomain = env('BYTE_SUB_DOMAIN')) {
            Route::domain($subdomain)->group(function () use ($path) {
                self::LoadRoute($path);
            });
        } else {
            self::LoadRoute($path);
        }
    }
}
