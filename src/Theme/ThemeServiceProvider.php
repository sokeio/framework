<?php

namespace Sokeio\Theme;

use Illuminate\Support\Facades\Blade;
use Sokeio\Theme;
use Sokeio\Theme\ThemeBladeDirectives;

class ThemeServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->singleton(\Illuminate\Contracts\Debug\ExceptionHandler::class, ThemeHandler::class);
        $this->app->singleton('sokeio_theme', ThemeManager::class);
        Blade::directive('themeBodyEnd', [ThemeBladeDirectives::class, 'themeBodyEnd']);
        Blade::directive('themeBody',  [ThemeBladeDirectives::class, 'themeBody']);
        Blade::directive('themeHead', [ThemeBladeDirectives::class, 'themeHead']);
    }
}
