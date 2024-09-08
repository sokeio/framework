<?php

namespace SokeioTheme\Admin;

use Illuminate\Support\ServiceProvider;
use Sokeio\Concerns\WithServiceProvider;
use Sokeio\Platform;
use Sokeio\ServicePackage;
use Sokeio\Theme;

class AdminServiceProvider extends ServiceProvider
{
    use WithServiceProvider;

    public function configurePackage(ServicePackage $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         */
        $package
            ->name('admin')
            ->hasConfigFile()
            ->hasViews()
            ->hasHelpers()
            ->hasAssets()
            ->hasTranslations()
            ->runsMigrations();
    }
    public function packageBooted()
    {
        if (Platform::isUrlAdmin()) {
            Theme::linkJs(
                url('platform/themes/admin/tabler/js/tabler.min.js'),
                'https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/js/tabler.min.js'
            );
            Theme::linkCss(
                url('platform/themes/admin/tabler/css/tabler.min.css'),
                'https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/css/tabler.min.css'
            );
            Theme::linkCss(
                url('platform/themes/admin/tabler-icon/tabler-icons.min.css'),
                'https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.14.0/tabler-icons.min.css'
            );
        }
    }
}
