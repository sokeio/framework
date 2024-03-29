<?php

namespace Sokeio\Locales;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Routing\UrlGenerator as LaravelUrlGenerator;

class LocaleServiceProvider extends LaravelServiceProvider
{

    public function register()
    {
        $this->app->extend(LaravelUrlGenerator::class, function ($generator) {
            return new UrlGenerator($this->app['router']->getRoutes(), $generator->getRequest());
        });
    }
}
