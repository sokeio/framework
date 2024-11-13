<?php

namespace Sokeio;

use Sokeio\Concerns\WithServiceProvider;
use Sokeio\Providers\MediaSignedServiceProvider;
use Sokeio\Support\Livewire\LivewireServiceProvider;
use Sokeio\Support\Platform\PlatformServiceProvider;
use Sokeio\Support\Theme\ThemeServiceProvider;
use Sokeio\Support\Widget\WidgetServiceProvider;

class SokeioServiceProvider extends \Illuminate\Support\ServiceProvider
{
    use WithServiceProvider;
    public function configurePackage(ServicePackage $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         */
        $package
            ->name('sokeio')
            ->hasConfigFile(['sokeio', 'sokeio-stubs'])
            ->routeWeb()
            ->hasViews()
            ->hasHelpers()
            ->hasAssets()
            ->hasTranslations()
            ->runsMigrations();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function registeringPackage()
    {
        WatchTime::start();
        $this->app->register(ThemeServiceProvider::class);
        $this->app->register(PlatformServiceProvider::class);
        $this->app->register(LivewireServiceProvider::class);
        $this->app->register(WidgetServiceProvider::class);
        $this->app->register(MediaSignedServiceProvider::class);
        Theme::bodyAfter(function () {
            if (setting('SOKEIO_SHOW_PROGRESS_TIMER')) {
                echo '<p class="p-1 text-center"> Process Time: ' . WatchTime::showSeconds() . 's</p>';
            }
            if (setting('SOKEIO_SHOW_POSITION_DEBUG') && Platform::isUrlAdmin()) {
                echo '<script>document.body.classList.add("so-position-show-debug");</script>';
            }
        });
    }
}
