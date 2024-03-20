<?php

namespace Sokeio;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Sokeio\Laravel\ServicePackage;
use Sokeio\Directives\PlatformBladeDirectives;
use Sokeio\Exceptions\ThemeHandler;
use Sokeio\Facades\Assets;
use Sokeio\Facades\Module;
use Sokeio\Facades\Platform;
use Sokeio\Facades\Plugin;
use Sokeio\Facades\Theme;
use Sokeio\Locales\LocaleServiceProvider;
use Sokeio\Middleware\ThemeLayout;
use Sokeio\Concerns\WithServiceProvider;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Request;
use Livewire\Livewire;
use Sokeio\Facades\Menu;
use Sokeio\Facades\MenuRender;
use Sokeio\Facades\Shortcode;
use Sokeio\Icon\IconManager;
use Sokeio\Livewire\MenuItemLink;
use Sokeio\Menu\MenuBuilder;
use Sokeio\Shortcode\ShortcodeserviceProvider;
use Sokeio\Support\SupportFormObjects\SupportFormObjects;
use Sokeio\Middleware\SokeioPlatform;
use Sokeio\Middleware\SokeioWeb;

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

    protected function registerBladeDirectives()
    {
        //Blade directives
        Blade::directive('ThemeBody', [PlatformBladeDirectives::class, 'themeBody']);
        Blade::directive('ThemeHead', [PlatformBladeDirectives::class, 'themeHead']);
        Blade::directive('role',  [PlatformBladeDirectives::class, 'role']);
        Blade::directive('endrole', [PlatformBladeDirectives::class, 'endRole']);
        Blade::directive('permission',  [PlatformBladeDirectives::class, 'permission']);
        Blade::directive('endPermission', [PlatformBladeDirectives::class, 'endPermission']);
    }

    protected function registerMiddlewares()
    {
        /** @var \Illuminate\Routing\Router $router */
        $router = $this->app['router'];

        $router->aliasMiddleware('themelayout', ThemeLayout::class);
    }

    public function packageBooted()
    {
        config(['auth.providers.users.model' => config('sokeio.model.user')]);
        $this->app->register(LocaleServiceProvider::class);
        $this->app->register(ShortcodeserviceProvider::class);
    }
    public function bootingPackage()
    {
        Module::loadApp();
        Theme::loadApp();
        Plugin::loadApp();
    }
    private function addTrigger()
    {
        addAction(PLATFORM_HEAD_BEFORE, function () {
            echo '<meta charset="utf-8">';
            echo '<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">';
            echo '<meta http-equiv="X-UA-Compatible" content="ie=edge">';
            echo '<meta name="csrf_token" value="' . csrf_token() . '"/>';

            if (!sokeioIsAdmin() && function_exists('seo_header_render')) {
                addFilter('SEO_DATA_DEFAULT', function ($prev) {
                    return [
                        ...$prev,
                        'title' => Assets::getTitle() ?? $prev['title'],
                        'description' => Assets::getDescription() ?? $prev['description'],
                        'favicon' => Assets::getFavicon() ?? $prev['favicon']
                    ];
                });
                echo '<!---SEO:BEGIN--!>';
                echo seo_header_render();
                echo '<!---SEO:END--!>';
            } else {
                if ($title = Assets::getTitle()) {
                    echo "<title>" . $title . "</title>";
                }
                if ($descripiton = Assets::getDescription()) {
                    echo "<meta name='description' content='" . $descripiton . "'/>";
                }
            }
        }, 0);
        addAction(PLATFORM_HEAD_AFTER, function () {
            echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles();
            Assets::Render(PLATFORM_HEAD_AFTER);
        });
        addAction(PLATFORM_BODY_AFTER, function () {

            echo  \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scriptConfig();
            echo  \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts();
            $scriptSokeio = file_get_contents(__DIR__ . '/../sokeio.js');
            $arrConfigjs = [
                'url' => url(''),
                'sokeio_url' => route('__sokeio__'),
                'csrf_token' => csrf_token(),
                'sokeio_filemanager' => route('unisharp.lfm.show'),
                'sokeio_icon_setting' => [
                    'url' => route('admin.icon-setting'),
                    'title' => __('Icon Setting'),
                    'size' => 'modal-fullscreen-md-down modal-xl'
                ],
                'sokeio_color_setting' => [
                    'url' => route('admin.color-setting'),
                    'title' => __('Color Setting'),
                    'size' => ''
                ],
                'sokeio_shortcode_setting' => [
                    'title' => __('Shortcode Setting'),
                    'url' => route('admin.shortcode-setting'),
                    'size' => 'modal-fullscreen-md-down modal-xl',
                ],
                'tinyecm_option' => [
                    "relative_urls" => false,
                    "content_style" => "
                    ",
                    "menubar" => true,
                    "plugins" => [
                        "advlist", "autolink", "lists", "link", "image", "charmap", "preview", "anchor",
                        "searchreplace", "visualblocks", "code", "fullscreen",
                        "insertdatetime", "media", "table",  "code", "help", "wordcount",
                        "shortcode"
                    ],
                    "toolbar" =>
                    "undo redo |shortcode link image |  formatselect | " .
                        "bold italic backcolor | alignleft aligncenter " .
                        "alignright alignjustify | bullist numlist outdent indent | " .
                        "removeformat | help",
                ]
            ];
            echo "
            <script data-navigate-once type='text/javascript' id='sokeioManagerjs____12345678901234567'>
            eval(atob(\"" . base64_encode($scriptSokeio . "
            
            window.addEventListener('sokeio::init',function(){
                if(window.SokeioManager){
                    window.SokeioManager.\$debug=" . (env('SOKEIO_MODE_DEBUG', false) ? 'true' : 'false') . ";
                    window.SokeioManager.\$config=" . json_encode(applyFilters(PLATFORM_CONFIG_JS,  $arrConfigjs)) . ";
                }
            });
            setTimeout(function(){
                document.getElementById('sokeioManagerjs____12345678901234567')?.remove();
            },400)") . "\"));
            </script>";
            Assets::Render(PLATFORM_BODY_AFTER);

            if (
                !sokeioIsAdmin() &&
                setting('COOKIE_BANNER_ENABLE', 1) &&
                Request::isMethod('get') &&
                !request()->cookie('cookie-consent') &&
                Platform::checkConnectDB()
            ) {
                echo Livewire::mount('sokeio::gdpr-modal');
            }
        });

        addAction('SEO_SITEMAP', function () {
            Shortcode::disable();
        });
    }
    private function triggerApp()
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
    }
    private function triggerPlatform()
    {
        if (adminUrl() != '') {
            Platform::routeSiteBeforeReady(function () {
                Route::get('/', routeFilter(
                    PLATFORM_HOMEPAGE,
                    'sokeio::homepage'
                ))->name('homepage');
            });
        }
        addFilter(PLATFORM_HOMEPAGE, function ($prev) {
            if (function_exists('SeoHelper')) {
                SeoHelper()->SEODataTransformer(function ($data) {
                    $data['title'] = setting('PLATFORM_HOMEPAGE_TITLE');
                    $data['description'] = setting('PLATFORM_HOMEPAGE_DESCRIPTION');
                    return $data;
                });
            }

            Assets::setTitle(setting('PLATFORM_HOMEPAGE_TITLE'));
            Assets::setDescription(setting('PLATFORM_HOMEPAGE_DESCRIPTION'));
            return $prev;
        });

        Platform::ready(function () {
            MenuRender::RegisterType(MenuItemLink::class);
            if (Request::isMethod('get')) {
                if (!Platform::checkFolderPlatform()) {
                    Platform::makeLink();
                }
                IconManager::getInstance()->assets();
            }

            if (sokeioIsAdmin()) {
                Menu::Register(function () {
                    menuAdmin()->attachMenu('system_setting_menu', function (MenuBuilder $menu) {
                        $menu->route(
                            'admin.system.clean-system-tool',
                            'Clean System Tool ',
                            '',
                            [],
                            'admin.system.clean-system-tool'
                        );
                        $menu->route(
                            'admin.system.cookies-setting',
                            __('Cookie Banner'),
                            '',
                            [],
                            'admin.system.cookies-setting'
                        );
                        $menu->route(
                            'admin.system.permalink-setting',
                            __('Permalink'),
                            '',
                            [],
                            'admin.system.permalink-setting'
                        );
                    });
                });
                addFilter('SOKEIO_MENU_ITEM_MANAGER', function ($prev) {
                    return [
                        ...$prev,
                        ...MenuRender::getMenuType()->map(function ($item) {
                            return [
                                'title' => $item['title'],
                                'key' => $item['type'],
                                'body' => livewireRender($item['setting']),
                            ];
                        })
                    ];
                }, 0);
            }
        });
    }
    public function packageRegistered()
    {
        $this->triggerApp();
        $this->addTrigger();
        $this->triggerPlatform();
        $this->registerMiddlewares();
        Collection::macro('paginate', function ($pageSize) {
            return ColectionPaginate::paginate($this, $pageSize);
        });

        $this->registerBladeDirectives();

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
                !Platform::checkConnectDB() &&
                request()->isMethod('get')
            ) {
                app(Redirector::class)->to(route('sokeio.setup'))->send();
                return;
            }
            Route::pushMiddlewareToGroup('web', SokeioWeb::class);
            Route::pushMiddlewareToGroup('web', SokeioPlatform::class);
            Route::pushMiddlewareToGroup('api', SokeioPlatform::class);
            if (isLivewireRequest()) {
                Theme::reTheme();
            }
        });

        Route::fallback(function () {
            if (!Platform::checkConnectDB() && request()->isMethod('get')) {
                app(Redirector::class)->to(route('sokeio.setup'))->send();
            }
            return abort(404);
        });
    }
}
