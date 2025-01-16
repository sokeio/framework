<?php

namespace Sokeio\Core;

use Illuminate\Support\Facades\Route;
use Sokeio\Http\Middleware\Authenticate;
use Sokeio\Http\Middleware\JwtUser;
use Sokeio\Platform;

class PlatformServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        Route::pushMiddlewareToGroup('sokeio.api', 'api');
        Route::pushMiddlewareToGroup('sokeio.api', JwtUser::class);
        Route::pushMiddlewareToGroup('sokeio.api', Authenticate::class);
        Route::pushMiddlewareToGroup('sokeio.api.guest', 'api');
        Route::pushMiddlewareToGroup('sokeio.web', 'web');
        Route::pushMiddlewareToGroup('sokeio.web.auth', 'web');
        Route::pushMiddlewareToGroup('sokeio.web.auth', Authenticate::class);
        Route::pushMiddlewareToGroup('sokeio.admin', 'web');
        Route::pushMiddlewareToGroup('sokeio.admin', Authenticate::class);
        Route::pushMiddlewareToGroup('sokeio.admin.guest', 'web');
        Platform::loadFromPath(Platform::getPlatformPath());
        $this->app->booted(function () {
            Platform::boot();
        });
    }
}
