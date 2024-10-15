<?php

namespace Sokeio\Support\Livewire;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Sokeio\Platform;
use Sokeio\Support\Menu\MenuItem;
use Sokeio\Support\Menu\MenuManager;
use Sokeio\Theme;

class PageConfig
{
    public function __construct(protected $component = null) {}

    private $config = [
        'layout' => 'default',
        'title' => '',
        'url' => null,
        'icon' => null,
        'route' => null,
        'admin' => false,
        'auth' => true,
        'menu' => false,
        'menuTitle' => null,
        'menuIcon' => null,
        'menuTarget' => null,
        'menuTargetTitle' => null,
        'menuTargetIcon' => null,
        'menuTargetId' => null,
        'menuTargetClass' => null,
        'skipHtmlAjax' => false
    ];
    public function setInfo(PageInfo $info)
    {
        foreach ($this->config as $key => $value) {
            if ($info->{$key} === null) {
                continue;
            }
            $this->config[$key] = $info->{$key};
        }
    }
    public function skipHtmlAjax()
    {
        return $this->config['skipHtmlAjax'] ?? false;
    }
    public function __call($name, $arguments)
    {
        $prefix = substr($name, 0, 3);
        $name = substr($name, 3);
        $nameLowerFirst = Str::lcfirst($name);
        if ('set' === $prefix) {
            $this->config[$nameLowerFirst] = $arguments[0];
            return $this;
        }

        if ('get' === $prefix) {
            return data_get($this->config, $nameLowerFirst, isset($arguments[0]) ? $arguments[0] : null);
        }

        return $this->{$name}(...$arguments);
    }
    public static function setupLayout($target, &$layoutConfig)
    {
        $config = $target->getPageConfig();
        if (!$config->skipHtmlAjax() && (Request::ajax() || Request::wantsJson())) {
            $layoutConfig->view = "sokeio::html";
        } else {
            $layoutConfig->view = Theme::getLayout($config->getLayout());
        }
        Theme::title($config->getTitle());
    }
    public static function setupRoute($pageClass, $namespaceRoot = null, $shortName = null)
    {
        $config = app($pageClass)->getPageConfig();
        $namespacePage = $namespaceRoot . '\\Page\\';
        // do nothing
        $url = str($pageClass)->after($namespacePage);
        $urlRoute = $config->getUrl() ?? str($url)->split('/\\\\/', -1, PREG_SPLIT_NO_EMPTY)
            ->map([Str::class, 'kebab'])->join('/');
        $nameRoute = $config->getRoute()
            ??  ($shortName . '-page.' . str($url)->split('/\\\\/', -1)->map([Str::class, 'kebab'])->join('.'));

        if ($config->getAdmin()) {
            if ($config->getAuth() && $config->getMenu()) {
                $menuTitle = $config->getMenuTitle() ?? str(str($nameRoute)->afterLast('.'))->replace('-', ' ');
                $target = $config->getMenuTarget();
                if (!$target && count(str($nameRoute)->split('/\./', -1, PREG_SPLIT_NO_EMPTY)) > 2) {
                    $target = str($nameRoute)->beforeLast('.');
                }
                MenuManager::registerItem(
                    MenuItem::make($nameRoute, str($menuTitle)->title()->replace('-', ' '), null)
                        ->route('admin.' . $nameRoute)
                        ->setup(function (MenuItem $item) use ($target) {
                            if ($target) {
                                $item->target =  $target;
                            }
                        })
                );
                if($target){
                    MenuManager::targetSetup($target, function (MenuItem $item) use ($config) {
                        dd($config);
                        if($config->getMenuTargetClass()){
                            // $item-> = $config->getMenuTargetClass();
                        }
                        if($config->getMenuTargetId()){
                            $item->id = $config->getMenuTargetId();
                        }
                        if($config->getMenuTargetIcon()){
                            $item->icon = $config->getMenuTargetIcon();
                        }
                        if($config->getMenuTargetTitle()){
                            $item->title = $config->getMenuTargetTitle();
                        }
                    });
                }
            }
            Platform::routeAdmin(function () use ($pageClass, $urlRoute, $nameRoute) {
                Route::get($urlRoute, $pageClass)->name($nameRoute);
            }, !$config->getAuth());
        } else {
            Platform::routeWeb(function () use ($pageClass, $urlRoute, $nameRoute) {
                Route::get($urlRoute, $pageClass)->name($nameRoute);
            }, $config->getAuth());
        }
    }
    public function toArray()
    {
        return $this->config;
    }
}
