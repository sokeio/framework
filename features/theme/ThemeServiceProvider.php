<?php

namespace Sokeio\Theme;

use Illuminate\Support\Facades\Blade;
use Sokeio\Platform;

class ThemeServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->singleton(\Illuminate\Contracts\Debug\ExceptionHandler::class, ThemeHandler::class);

        $this->registerDirectives();
        Platform::addLoader(TemplateLoader::class);
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
