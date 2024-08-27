<?php

namespace Sokeio\Platform;

use Sokeio\Platform;

class PlatformServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->singleton('sokeio_platform', PlatformManager::class);
        $this->app->singleton('sokeio_platform_loader', PlatformLoaderManager::class);
        Platform::loadFromPath(Platform::platformPath());
        $this->app->booting(function () {
            Platform::loader();
            Platform::boot();
        });
    }
}
