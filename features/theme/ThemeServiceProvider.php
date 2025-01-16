<?php

namespace Sokeio\Theme;

use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use Sokeio\Theme;

class ThemeServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->singleton(\Illuminate\Contracts\Debug\ExceptionHandler::class, ThemeHandler::class);

        $this->registerDirectives();
        
    }
    /**
     * Register all directives.
     *
     * @return void
     */
    public function registerDirectives()
    {
        $directives = require __DIR__ . '/directives.php';

        collect($directives)->each(function ($item, $key) {
            Blade::directive($key, $item);
        });
    }
}
