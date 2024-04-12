<?php

namespace Sokeio\Providers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Sokeio\Facades\Assets;
use Sokeio\Facades\MenuRender;
use Sokeio\Facades\Platform;
use Sokeio\Facades\Shortcode;
use Sokeio\Facades\Theme;
use Sokeio\Livewire\MenuItemLink;

class ViewTriggerServiceProvider extends ServiceProvider
{
    public function register()
    {
        MenuRender::RegisterType(MenuItemLink::class);
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

        addFilter(PLATFORM_HOMEPAGE, function ($prev) {
            Assets::setTitle(setting('PLATFORM_HOMEPAGE_TITLE'));
            Assets::setDescription(setting('PLATFORM_HOMEPAGE_DESCRIPTION'));
            return $prev;
        });
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
            $scriptSokeio = file_get_contents(__DIR__ . '/../../sokeio.js');
            $arrConfigjs = [
                'url' => asset(''),
                'sokeio_url' => route('__sokeio__'),
                'csrf_token' => csrf_token(),
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
}
