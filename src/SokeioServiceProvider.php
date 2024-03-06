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

class SokeioServiceProvider extends ServiceProvider
{
    use WithServiceProvider;
    public function configurePackage(ServicePackage $package): void
    {
        Platform::Start();
        $this->app->singleton(\Illuminate\Contracts\Debug\ExceptionHandler::class, ThemeHandler::class);
        $this->app->register(LocaleServiceProvider::class);


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
        Blade::directive('ThemeBody', [PlatformBladeDirectives::class, 'ThemeBody']);
        Blade::directive('ThemeHead', [PlatformBladeDirectives::class, 'ThemeHead']);
        Blade::directive('role',  [PlatformBladeDirectives::class, 'Role']);
        Blade::directive('endrole', [PlatformBladeDirectives::class, 'EndRole']);
        Blade::directive('permission',  [PlatformBladeDirectives::class, 'Permission']);
        Blade::directive('endPermission', [PlatformBladeDirectives::class, 'EndPermission']);
    }
    protected function registerProvider()
    {
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
        $this->app->register(ShortcodeserviceProvider::class);
    }
    public function bootingPackage()
    {
        Module::LoadApp();
        Theme::LoadApp();
        Plugin::LoadApp();
    }

    public function packageRegistered()
    {

        $this->registerMiddlewares();
        Collection::macro('paginate', function ($pageSize) {
            return ColectionPaginate::paginate($this, $pageSize);
        });

        $this->registerBladeDirectives();
        add_action(PLATFORM_HEAD_BEFORE, function () {
            echo '<meta charset="utf-8">';
            echo '<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">';
            echo '<meta http-equiv="X-UA-Compatible" content="ie=edge">';
            echo '<meta name="csrf_token" value="' . csrf_token() . '"/>';
            if (!sokeio_is_admin() && function_exists('seo_header_render')) {
                add_filter('SEO_DATA_DEFAULT', function ($prev) {
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
        add_action(PLATFORM_HEAD_AFTER, function () {
            echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles();
            Assets::Render(PLATFORM_HEAD_AFTER);
        });
        add_action(PLATFORM_BODY_AFTER, function () {

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
                    window.SokeioManager.\$config=" . json_encode(apply_filters(PLATFORM_CONFIG_JS,  $arrConfigjs)) . ";
                }
            });
            setTimeout(function(){
                document.getElementById('sokeioManagerjs____12345678901234567')?.remove();
            },400)") . "\"));
            </script>";
            Assets::Render(PLATFORM_BODY_AFTER);
            if (!sokeio_is_admin() && setting('COOKIE_BANNER_ENABLE', 1) && Request::isMethod('get') && !request()->cookie('cookie-consent')) {
                echo Livewire::mount('sokeio::gdpr-modal');
            }
        });
        $this->app->booting(function () {
            app('livewire')->componentHook(SupportFormObjects::class);
        });
        add_filter(PLATFORM_HOMEPAGE, function ($prev) {
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
        if (admin_url() != '') {
            Platform::RouteSiteBeforeReady(function () {
                Route::get('/', route_filter(
                    PLATFORM_HOMEPAGE,
                    'sokeio::homepage'
                ))->name('homepage');
            });
        }
        $this->app->booted(function () {
            Theme::RegisterRoute();
            RouteEx::Admin(function () {
                Platform::DoRouteAdminBeforeReady();
            });
            RouteEx::Web(function () {
                Platform::DoRouteSiteBeforeReady();
            });
            RouteEx::Api(function () {
                Platform::DoRouteApiBeforeReady();
            });
        });
        add_action('SEO_SITEMAP', function () {
            Shortcode::disable();
        });
        Platform::Ready(function () {
            if (Request::isMethod('get')) {
                if (!Platform::checkFolderPlatform()) {
                    Platform::makeLink();
                }
                IconManager::getInstance()->Assets();
                Menu::Register(function () {
                    if (!sokeio_is_admin()) return;
                    menu_admin()->attachMenu('system_setting_menu', function (MenuBuilder $menu) {
                        $menu->route('admin.cookies-setting', 'Cookie Banner', '', [], 'admin.cookies-setting');
                        $menu->route('admin.permalink-setting', 'Permalink', '', [], 'admin.permalink-setting');
                        $menu->route('admin.item', 'Items', '', [], 'admin.item');
                    });
                });
            }
            MenuRender::RegisterType(MenuItemLink::class);

            if (sokeio_is_admin()) {
                add_filter('SOKEIO_MENU_ITEM_MANAGER', function ($prev) {
                    return [
                        ...$prev,
                        ...MenuRender::getMenuType()->map(function ($item) {
                            return [
                                'title' => $item['title'],
                                'key' => $item['type'],
                                'body' => livewire_render($item['setting']),
                            ];
                        })
                    ];
                }, 0);
            }
        });

        Route::matched(function () {
            $route_name = Route::currentRouteName();
            if ($route_name == 'homepage' && admin_url() == '') {
                add_filter(SOKEIO_IS_ADMIN, function () {
                    return true;
                }, 0);
            }
            if ($route_name && $route_name != 'sokeio.setup' && !Platform::CheckConnectDB() && request()->isMethod('get')) {
                app(Redirector::class)->to(route('sokeio.setup'))->send();
                return;
            }
            Theme::reTheme();
            Theme::SetupOption();
            Platform::BootGate();
            Platform::DoReady();
            Platform::DoReadyAfter();
        });

        Route::fallback(function () {
            if (!Platform::CheckConnectDB() && request()->isMethod('get')) {
                app(Redirector::class)->to(route('sokeio.setup'))->send();
            }
            return abort(404);
        });
    }
}
