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

        config(['auth.providers.users.model' => config('sokeio.model.user')]);
        $this->registerBladeDirectives();
        add_action(PLATFORM_HEAD_BEFORE, function () {
            echo '<meta charset="utf-8">';
            echo '<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">';
            echo '<meta http-equiv="X-UA-Compatible" content="ie=edge">';
            echo '<meta name="csrf_token" value="' . csrf_token() . '"/>';
            if (!sokeio_is_admin() && function_exists('seo_header_render')) {
                echo '<!---SEO:BEGIN--!>';
                echo call_user_func('seo_header_render');
                echo '<!---SEO:END--!>';
            } else  if ($title = Theme::getTitle()) {
                echo "<title>" . $title . "</title>";
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
            " . $scriptSokeio . "
            
            window.addEventListener('sokeio::init',function(){
                if(window.SokeioManager){
                    window.SokeioManager.\$debug=" . (env('SOKEIO_MODE_DEBUG', false) ? 'true' : 'false') . ";
                    window.SokeioManager.\$config=" . json_encode(apply_filters(PLATFORM_CONFIG_JS,  $arrConfigjs)) . ";
                }
            });
            setTimeout(function(){
                document.getElementById('sokeioManagerjs____12345678901234567')?.remove();
            },400)
            </script>";
            Assets::Render(PLATFORM_BODY_AFTER);
        });
        $this->app->booting(function () {
            app('livewire')->componentHook(SupportFormObjects::class);
        });
        $this->app->booted(function () {
            Theme::RegisterRoute();
            if (adminUrl() != '') {
                Route::get('/', route_theme(function () {
                    $homepage = apply_filters(PLATFORM_HOMEPAGE, 'sokeio::homepage');
                    if (is_object($homepage)) return $homepage;
                    $view = '';
                    $params = [];
                    if (is_array($homepage)) {
                        ['view' => $view, 'params' => $params] = $homepage;
                    } else {
                        $view = $homepage;
                    }
                    return view_scope($view, $params);
                }))->name('homepage');
            }
        });
        Platform::Ready(function () {
            if (Request::isMethod('get')) {
                if (!Platform::checkFolderPlatform()) {
                    Platform::makeLink();
                }
            }
        });
        Route::matched(function () {
            $route_name = Route::currentRouteName();
            if ($route_name == 'homepage' && adminUrl() == '') {
                add_filter(SOKEIO_IS_ADMIN, function () {
                    return true;
                }, 0);
            }
            $route_name = Route::currentRouteName();
            if ($route_name && $route_name != 'sokeio.setup' && !Platform::CheckConnectDB() && request()->isMethod('get')) {
                app(Redirector::class)->to(route('sokeio.setup'))->send();
                return;
            }
            Theme::reTheme();
            Platform::BootGate();
            Platform::DoReady();
            Platform::DoReadyAfter();
        });

        Route::fallback(function () {
            if (!Platform::CheckConnectDB() && request()->isMethod('get')) {
                app(Redirector::class)->to(route('sokeio.setup'))->send();
                return;
            }
        });
    }
}
