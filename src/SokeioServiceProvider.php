<?php

namespace Sokeio;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Sokeio\Laravel\ServicePackage;
use Sokeio\Exceptions\ThemeHandler;
use Sokeio\Facades\Module;
use Sokeio\Facades\Platform;
use Sokeio\Facades\Plugin;
use Sokeio\Facades\Theme;
use Sokeio\Locales\LocaleServiceProvider;
use Sokeio\Middleware\ThemeLayout;
use Sokeio\Concerns\WithServiceProvider;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Sokeio\Icon\IconManager;
use Sokeio\Livewire\HomePage;
use Sokeio\Shortcode\ShortcodeserviceProvider;
use Sokeio\Support\SupportFormObjects\SupportFormObjects;
use Sokeio\Middleware\SokeioPlatform;
use Sokeio\Middleware\SokeioWeb;
use Sokeio\Providers\MenuAdminServiceProvider;
use Sokeio\Providers\PlatformExtentionServiceProvider;
use Sokeio\Providers\ViewTriggerServiceProvider;
use Sokeio\Widgets\WidgetServiceProvider;

class SokeioServiceProvider extends ServiceProvider
{
    use WithServiceProvider;
    public function configurePackage(ServicePackage $package): void
    {
        Platform::start();
        $this->app->singleton(\Illuminate\Contracts\Debug\ExceptionHandler::class, ThemeHandler::class);
        /*
         * This class is a Package Service Provider
         *
         */
        $package
            ->name('sokeio')
            ->hasConfigFile()
            ->hasViews()
            ->hasHelpers()
            ->hasAssets()
            ->hasTranslations()
            ->runsMigrations()
            ->runsSeeds();
    }
    public function bootingPackage()
    {
        Module::loadApp();
        Theme::loadApp();
        Plugin::loadApp();
    }

    public function packageBooted()
    {
        config(['auth.providers.users.model' => config('sokeio.model.user')]);
        $this->app->register(LocaleServiceProvider::class);
        $this->app->register(ShortcodeserviceProvider::class);
        $this->app->register(MenuAdminServiceProvider::class);
        $this->app->register(ViewTriggerServiceProvider::class);
        $this->app->register(PlatformExtentionServiceProvider::class);
        $this->app->register(WidgetServiceProvider::class);
    }
    public function packageRegistered()
    {
        $this->registerPlatform();
        $this->registerRoute();
    }

    private function registerPlatform()
    {
        $this->app->booted(function () {
            Theme::RegisterRoute();
            RouteEx::admin(function () {
                Platform::doRouteAdminBeforeReady();
            });
            RouteEx::web(function () {
                Platform::doRouteSiteBeforeReady();
            });
            RouteEx::api(function () {
                Platform::doRouteApiBeforeReady();
            });
        });
        $this->app->booting(function () {
            app('livewire')->componentHook(SupportFormObjects::class);
        });
        if (adminUrl() != '') {
            Platform::routeSiteBeforeReady(function () {
                Route::get('/', routeFilter(
                    PLATFORM_HOMEPAGE,
                    HomePage::class
                ))->name('homepage');
            });
        }


        Platform::ready(function () {
            if (Request::isMethod('get')) {
                if (!Platform::checkFolderPlatform()) {
                    Platform::makeLink();
                }
                IconManager::getInstance()->assets();
            }
            if (Theme::checkSite()) {
                addAction('THEME_ADMIN_RIGHT', function () {
                    echo '<div class="nav-item">
                    <a class="nav-link fw-bold" target="_blank" href="' . url('/') . '">
                    ' . __('Visit website') . '
                    </a>
                    </div>';
                });
            }
        });
    }
    private function registerRoute()
    {
        /** @var \Illuminate\Routing\Router $router */
        $router = $this->app['router'];

        $router->aliasMiddleware('themelayout', ThemeLayout::class);
        Route::matched(function () {
            $route_name = Route::currentRouteName();
            if ($route_name == 'homepage' && adminUrl() == '') {
                addFilter(SOKEIO_IS_ADMIN, function () {
                    return true;
                }, 0);
            }
            if (
                $route_name &&
                $route_name != 'sokeio.setup' &&
                request()->isMethod('get') &&
                Platform::checkSetupUI()
            ) {
                app(Redirector::class)->to(route('sokeio.setup'))->send();
                return;
            }
            Route::pushMiddlewareToGroup('web', SokeioWeb::class);
            Route::pushMiddlewareToGroup('web', SokeioPlatform::class);
            Route::pushMiddlewareToGroup('api', SokeioPlatform::class);
        });

        Route::fallback(function () {
            if (!Platform::checkConnectDB() && request()->isMethod('get')) {
                app(Redirector::class)->to(route('sokeio.setup'))->send();
            }
            return abort(404);
        });
    }
}
