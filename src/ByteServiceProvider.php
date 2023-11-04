<?php

namespace BytePlatform;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use BytePlatform\Laravel\ServicePackage;
use BytePlatform\Directives\PlatformBladeDirectives;
use BytePlatform\Exceptions\ThemeHandler;
use BytePlatform\Facades\Assets;
use BytePlatform\Facades\Gate;
use BytePlatform\Facades\Menu;
use BytePlatform\Facades\Module;
use BytePlatform\Facades\Platform;
use BytePlatform\Facades\Plugin;
use BytePlatform\Facades\Theme;
use BytePlatform\Locales\LocaleServiceProvider;
use BytePlatform\Middleware\ThemeLayout;
use BytePlatform\Shortcode\ShortcodesServiceProvider;
use BytePlatform\Concerns\WithServiceProvider;

class ByteServiceProvider extends ServiceProvider
{
    use WithServiceProvider;
    public function configurePackage(ServicePackage $package): void
    {

        $this->app->singleton(\Illuminate\Contracts\Debug\ExceptionHandler::class, ThemeHandler::class);
        $this->app->register(LocaleServiceProvider::class);
        $this->app->register(ShortcodesServiceProvider::class);

        /*
         * This class is a Package Service Provider
         *
         */
        $package
            ->name('byte')
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
        /** @var Router $router */
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
        Collection::macro('paginate', function ($pageSize) {
            return ColectionPaginate::paginate($this, $pageSize);
        });

        $this->registerMiddlewares();

        if ($this->app->runningInConsole())
            return;
        config(['auth.providers.users.model' => config('byte.model.user')]);
        $this->registerBladeDirectives();
        add_action(PLATFORM_HEAD_BEFORE, function () {
            echo '<meta charset="utf-8">';
            echo '<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">';
            echo '<meta http-equiv="X-UA-Compatible" content="ie=edge">';
            echo '<meta name="csrf_token" value="' . csrf_token() . '"/>';
            if (!byte_is_admin() && function_exists('seo_header_render')) {
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
            $scriptBytePlatform = file_get_contents(__DIR__ . '/../byte.js');
            $arrConfigjs = [
                'url' => url(''),
                'byte_url' => route('__byte__'),
                'csrf_token' => csrf_token(),
                'byte_filemanager' => route('unisharp.lfm.show'),
                'byte_shortcode_setting' => route('shortcode-setting'),
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
            <script data-navigate-once type='text/javascript' id='ByteManagerjs____12345678901234567'>
            " . $scriptBytePlatform . "
            
            window.addEventListener('byte::init',function(){
                if(window.ByteManager){
                    window.ByteManager.\$debug=" . (env('BYTE_MODE_DEBUG', false) ? 'true' : 'false') . ";
                    window.ByteManager.\$config=" . json_encode(apply_filters(PLATFORM_CONFIG_JS,  $arrConfigjs)) . ";
                }
            });
            setTimeout(function(){
                document.getElementById('ByteManagerjs____12345678901234567')?.remove();
            },400)
            </script>";
            Assets::Render(PLATFORM_BODY_AFTER);
        });
        add_filter(PLATFORM_HOMEPAGE, function ($view) {
            return $view;
        }, 0);


        $this->app->booted(function () {
            Theme::RegisterRoute();
        });
        Route::matched(function ($route) {
            Gate::BootApp();
            if (Route::currentRouteName() == 'homepage' && adminUrl() == '') {
                add_filter(PLATFORM_IS_ADMIN, function () {
                    return true;
                }, 0);
            }
            Theme::reTheme();
            if (Request::isMethod('get')) {
                // Only Get Request
                if (!Platform::checkFolderPlatform()) Platform::makeLink();
                if (byte_is_admin()) {
                    Menu::DoRegister();
                }
            }
        });
    }
}
