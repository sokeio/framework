<?php

namespace SokeioTheme\Admin;

use Illuminate\Support\ServiceProvider;
use Sokeio\Components\UI;
use Sokeio\Laravel\ServicePackage;
use Sokeio\Concerns\WithServiceProvider;
use Sokeio\Facades\Platform;

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
            ->name('theme')
            ->hasConfigFile()
            ->hasViews()
            ->hasHelpers()
            ->hasAssets()
            ->hasTranslations()
            ->runsMigrations();
    }
    public function packageBooted()
    {

        Platform::ReadyAfter(function () {
            if (sokeioIsAdmin()) {

                addFilter('SOKEIO_ADMIN_SETTING_OVERVIEW', function ($prev) {
                    return [
                        UI::column6([
                            UI::select('PLATFORM_ADMIN_LAYOUT_DEFAULT')
                                ->label(__('Layout Of Admin'))
                                ->dataSource(function () {
                                    return [
                                        [
                                            'id' => 'default',
                                            'name' => 'Default'
                                        ],
                                        [
                                            'id' => 'default-navbar',
                                            'name' => 'Navbar'
                                        ]
                                    ];
                                })
                        ]),
                        ...$prev
                    ];
                });
                addFilter('PLATFORM_THEME_LAYOUT_DEFAULT', function ($prev) {
                    return setting('PLATFORM_ADMIN_LAYOUT_DEFAULT', 'default');
                });
            }
        });
    }
}
