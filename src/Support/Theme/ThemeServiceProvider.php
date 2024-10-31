<?php

namespace Sokeio\Support\Theme;

use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use Sokeio\Support\Theme\ThemeBladeDirectives;
use Sokeio\Theme;

class ThemeServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->singleton(\Illuminate\Contracts\Debug\ExceptionHandler::class, ThemeHandler::class);
        Blade::directive('themeBodyEnd', [ThemeBladeDirectives::class, 'themeBodyEnd']);
        Blade::directive('themeBody',  [ThemeBladeDirectives::class, 'themeBody']);
        Blade::directive('themeHead', [ThemeBladeDirectives::class, 'themeHead']);
        Blade::directive('themeInclude', [ThemeBladeDirectives::class, 'themeInclude']);
        Theme::bodyAfter(function () {
            echo Livewire::mount('sokeio::global-body');
        });
    }
}
