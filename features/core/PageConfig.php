<?php

namespace Sokeio\Core;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Sokeio\Attribute\PageInfo;
use Sokeio\Attribute\Permission;
use Sokeio\Pattern\Tap;
use Sokeio\Platform;
use Sokeio\Menu\MenuItem;
use Sokeio\Theme;

class PageConfig
{
    use Tap;
    public function __construct(protected $component = null) {}

    private $config = [
        'layout' => null,
        'title' => '',
        'url' => null,
        'icon' => ' ti ti-brand-databricks',
        'route' => null,
        'sort' => 99999,
        'admin' => false,
        'auth' => true,
        'menu' => false,
        'menuTitle' => null,
        'menuClass' => null,
        'menuIcon' => null,
        'menuTarget' => null,
        'menuTargetTitle' => null,
        'menuTargetIcon' => ' ti ti-table-down',
        'menuTargetId' => null,
        'menuTargetClass' => null,
        'menuTargetSort' => null,
        'skipPermision' => false,
        'skipHtmlAjax' => false,
        'model' => null,
        'skip' => false,
        'enable' => true,
        'enableKeyInSetting' => false
    ];
    public function setInfo(PageInfo $info)
    {
        foreach ($this->config as $key => $value) {
            if (!isset($info->{$key}) || $info->{$key} === null) {
                continue;
            }
            $this->config[$key] = $info->{$key};
        }
        $key = $this->getEnableKeyInSetting();
        if ($key && !setting($key, data_get($this->config, 'enable'))) {
            $this->config['skip'] = true;
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
            $arr = explode('::layouts.', $layoutConfig->view);
            if (count($arr) > 1) {
                $name =  $arr[1];
                $classContent = Theme::getTheme()->getLayoutData($name . '.classContent');
                View::share('themeLayoutClassContent', $classContent);
            }
        }
        $info = Theme::getSiteInfo();
        if (!isset($info['title']) || $info['title'] == '') {
            Theme::title($config->getTitle());
        }
    }
    public static function setupRoute($pageClass, $namespaceRoot = null, $shortName = null)
    {
        $config = app($pageClass)->getPageConfig();
        if ($config->getSkip()) {
            return;
        }
        $namespacePage = $namespaceRoot . '\\Page\\';
        if (str($shortName)->startsWith('theme.')) {
            $shortName = str($shortName)->replace('.', '-');
        }
        // do nothing
        $url = str($pageClass)->after($namespacePage);
        $urlRoute = $config->getUrl() ?? str($url)->split('/\\\\/', -1, PREG_SPLIT_NO_EMPTY)
            ->map([Str::class, 'kebab'])->join('/');
        // if $urlRoute last character is '/index' , it will not work
        if (str($urlRoute)->endsWith('/index')) {
            $urlRoute = str($urlRoute)->replace('/index', '');
        }
        $key = str($url)->split('/\\\\/', -1)->map([Str::class, 'kebab'])->join('.');
        if (str($key)->endsWith('.index')) {
            $key = str($key)->replace('.index', '');
        }
        $nameRoute = $config->getRoute()
            ??  ($shortName . '-page.' . $key);
        $key = 'sokeio-menu.' . $key;
        $menuTitle = $config->getMenuTitle();
        if (!$menuTitle) {
            $menuTitle = $config->getTitle();
        }
        if (!$menuTitle) {
            $menuTitle = str(str($nameRoute)->afterLast('.'))->replace('-', ' ');
        }

        if ($config->getAdmin()) {
            if ($config->getAuth() && !$config->getSkipPermision()) {
                $per = 'admin.' . $nameRoute;
                Platform::gate()->register($per, $menuTitle, $shortName);
                foreach (Permission::fromClassMuti($pageClass) as $itemPer) {
                    Platform::gate()->register($itemPer->getKey(), $itemPer->getName(), $shortName);
                }
            }

            if (
                $config->getAuth()
                && $config->getMenu()
                && Platform::isUrlAdmin()
            ) {
                $menuClass = $config->getMenuClass();
                $target = $config->getMenuTarget();
                $sort = $config->getSort();
                $icon = $config->getIcon();
                $level = count(str($key)->split('/\./', -1, PREG_SPLIT_NO_EMPTY));
                if (!$target && $level > 2) {
                    $target = str($key)->beforeLast('.');
                }
                Platform::menu()->register(
                    MenuItem::make($key, str($menuTitle)->title()->replace('-', ' '), null)
                        ->route('admin.' . $nameRoute)
                        ->tap(function (MenuItem $item) use ($target, $sort, $icon, $menuClass) {
                            if ($menuClass) {
                                $item->classItem = $menuClass;
                            }
                            if ($target) {
                                $item->target =  $target;
                            }
                            if ($icon) {
                                $item->icon = $icon;
                            }
                            $item->sort = $sort;
                        })->permission('admin.' . $nameRoute)
                );
                if ($target) {
                    Platform::menu()->target($target, function (MenuItem $item) use ($config) {
                        if ($config->getMenuTargetClass()) {
                            $item->classItem = $config->getMenuTargetClass();
                        }
                        if ($config->getMenuTargetSort()) {
                            $item->sort = $config->getMenuTargetSort();
                        }
                        if ($config->getMenuTargetIcon()) {
                            $item->icon = $config->getMenuTargetIcon();
                        }
                        if ($config->getMenuTargetTitle()) {
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
