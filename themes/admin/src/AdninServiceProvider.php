<?php

namespace SokeioTheme\Adnin;

use Illuminate\Support\ServiceProvider;
use Sokeio\Concerns\WithServiceProvider;
use Sokeio\ServicePackage;

class AdninServiceProvider extends ServiceProvider
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
}
