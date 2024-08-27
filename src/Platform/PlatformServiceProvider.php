<?php

namespace Sokeio\Platform;

use Sokeio\Platform;

class PlatformServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->singleton('sokeio_platform', PlatformManager::class);
        Platform::loadFromPath(Platform::getPath());
        $this->app->booting(function () {
            Platform::boot();
        });
    }
}
