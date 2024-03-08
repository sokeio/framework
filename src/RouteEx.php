<?php

namespace Sokeio;

use Illuminate\Support\Facades\Route;

use Sokeio\Middleware\Platform as MiddlewarePlatform;

class RouteEx
{
    private static $cacheRouteLoaded = [];
    public static function checkPath($path)
    {
        if (isset(self::$cacheRouteLoaded[$path])) {
            return self::$cacheRouteLoaded[$path];
        }
        return false;
    }
    public static function api($callback)
    {

        Route::middleware(apply_filters(PLATFORM_MIDDLEWARE_API, ['api', MiddlewarePlatform::class]))
            ->prefix('api')
            ->group($callback);
    }
    public static function web($callback)
    {
        Route::middleware(apply_filters(PLATFORM_MIDDLEWARE_WEB, [MiddlewarePlatform::class, 'web']))
            ->group($callback);
    }
    public static function admin($callback)
    {
        Route::middleware(apply_filters(PLATFORM_MIDDLEWARE_ADMIN, [
            'web',
            \Sokeio\Middleware\Authenticate::class,
            \Sokeio\Middleware\ThemeAdmin::class,
            MiddlewarePlatform::class
        ]))
            ->prefix(adminUrl())
            ->group($callback);
    }
    private static function loadFile($path, $prefix)
    {
        if (file_exists($path . $prefix . '.php')) {
            includeFile($path . $prefix . '.php');
        }
        if (file_exists($path . $prefix)) {
            getAllFile($path . $prefix, function ($file) {
                includeFile($file);
            }, null);
        }
    }
    private static function loadRoute($path)
    {
        self::api(function () use ($path) {
            self::loadFile($path, 'api');
        });
        self::web(function () use ($path) {
            self::loadFile($path, 'web');
        });
        self::admin(function () use ($path) {
            self::loadFile($path, 'admin');
        });
    }
    public static function load($path)
    {
        if (self::checkPath($path)) {
            return;
        }
        self::$cacheRouteLoaded[$path] = true;

        if ($subdomain = env('SOKEIO_SUB_DOMAIN')) {
            Route::domain($subdomain)->group(function () use ($path) {
                self::loadRoute($path);
            });
        } else {
            self::loadRoute($path);
        }
    }
}
