<?php

namespace Sokeio;

use Livewire\Livewire;
use Sokeio\Concerns\WithServiceProvider;
use Sokeio\Providers\SocialiteServiceProvider;
use Sokeio\Livewire\LivewireServiceProvider;
use Sokeio\MediaStorage\MediaStorageServiceProvider;
use Sokeio\Core\PlatformServiceProvider;
use Sokeio\Dashboard\DashboardServiceProvider;
use Sokeio\Enums\AlertType;
use Sokeio\Enums\UIKey;
use Sokeio\Theme\ThemeServiceProvider;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Common\Card;
use Sokeio\UI\Field\Input;
use Sokeio\UI\Field\MediaIcon;
use Sokeio\UI\SoUI;

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
        $this->app->register(MediaStorageServiceProvider::class);
        $this->app->register(ThemeServiceProvider::class);
        $this->app->register(PlatformServiceProvider::class);
        $this->app->register(LivewireServiceProvider::class);
        $this->app->register(DashboardServiceProvider::class);
        $this->app->register(SocialiteServiceProvider::class);
        Theme::bodyAfter(function () {
            if (setting('SOKEIO_SHOW_PROGRESS_TIMER')) {
                echo '<p class="text-center text-dark position-fixed m-0 p-0 z-0 bottom-0 w-100 " > Load time: ' . WatchTime::showSeconds() . '</p>';
            }
            if (setting('SOKEIO_SHOW_POSITION_DEBUG') && Platform::isUrlAdmin()) {
                echo '<script>document.body.classList.add("so-position-show-debug");</script>';
            }
            echo Livewire::mount('sokeio::global-body');
        });
    }
    public function bootingPackage()
    {
        config(['auth.providers.users.model' => config('sokeio.model.user')]);

        // packageBooted
        Theme::location('SOKEIO_ADMIN_THEME_HEADER_RIGHT_BEFORE', function () {
            echo "<div class='btn-group me-2 py-1'>
                        <a href='" . url('/') . "' class='btn btn-primary' target='_blank'>Go To Site</a>
                    </div>";
        });
    }
}
