<?php

namespace Sokeio\Support\Theme;

use Illuminate\Support\Facades\View;
use Sokeio\Platform;
use Sokeio\Support\Theme\Concerns\ThemeData;
use Sokeio\Support\Theme\Concerns\ThemeHook;
use Sokeio\Support\Theme\Concerns\ThemeMeta;

class ThemeManager
{
    use ThemeHook, ThemeData, ThemeMeta;
    private $useCdn = false;
    private $layout;
    private $themeSite = null;
    private $themeAdmin = null;


    public function getThemeAdmin()
    {
        if (!$this->themeAdmin) {
            $this->themeAdmin = Platform::theme()->getActiveAll()->where('admin', true)->first();
        }
        return $this->themeAdmin;
    }
    public function getThemeSite()
    {
        if (!$this->themeSite) {
            $this->themeSite = Platform::theme()->getActiveAll()->where('admin', false)->first();
        }
        return $this->themeSite;
    }
    public function getTheme()
    {
        if (Platform::isUrlAdmin()) {
            return $this->getThemeAdmin();
        }

        return $this->getThemeSite();
    }

    public function enableCdn()
    {
        $this->useCdn = true;
        return $this;
    }
    public function disableCdn()
    {
        $this->useCdn = false;
        return $this;
    }

    public function headRender()
    {
        $this->metaRender();
        $this->callhook('headBefore');
        $this->renderData('linkCss', function ($value) {
            ['contentOrLink' => $contentOrLink, 'cdn' => $cdn] = $value;
            $link = $contentOrLink;
            if ($cdn && $this->useCdn || !$contentOrLink) {
                $link = $cdn;
            }
            echo "<link rel='stylesheet'  href='{$link}'/>";
        });
        $this->renderData('style', function ($value) {
            ['contentOrLink' => $contentOrLink, 'id' => $id] = $value;
            echo "<style type='text/css' id='{$id}'>{$contentOrLink}</style>";
        });
        $this->callhook('headAfter');
    }
    public function bodyRender()
    {
        $this->callhook('bodyBefore');
    }
    public function bodyAttrRender()
    {
        $this->callhook('bodyAttr');
    }
    public function bodyEndRender()
    {
        $this->renderData('linkJs', function ($value) {
            ['contentOrLink' => $contentOrLink, 'cdn' => $cdn, 'id' => $id] = $value;
            $link = $contentOrLink;

            if ($cdn && $this->useCdn || !$contentOrLink) {
                $link = $cdn;
            }
            if (!$id) {
                $id = md5($link);
            }

            echo "<script data-navigate-once type='text/javascript' id='{$id}' src='{$link}'></script>";
        });
        $this->renderData('js', function ($value) {
            ['contentOrLink' => $contentOrLink, 'id' => $id] = $value;
            $script = $contentOrLink;
            if (!$id) {
                $id = md5($script);
            }
            echo "<script data-navigate-once type='text/javascript' id='{$id}' >{$script}</script>";
        });
        $this->renderData('template', function ($value) {
            ['contentOrLink' => $contentOrLink, 'id' => $id] = $value;
            if (!$id) {
                $id = md5($contentOrLink);
            }
            echo "<template id='{$id}'>{$contentOrLink}</{template>";
        });
        $this->callhook('bodyAfter');
    }

    public function getNamespace($isAdmin = false)
    {
        return $isAdmin ? 'theme\\admin' : 'theme\\site';
    }
    public function namespaceTheme()
    {
        return $this->getNamespace(Platform::isUrlAdmin());
    }
    public function getLayoutName($name, $isAdmin = null)
    {
        if ($isAdmin === null) {
            $isAdmin = Platform::isUrlAdmin();
        }
        return $this->getNamespace($isAdmin) . '::layouts.' . $name;
    }
    public function view(string $view, array $data = [], array $mergeData = [], $noScope = false)
    {
        $viewParts = explode('::', $view);

        if (count($viewParts) === 1) {
            $view = $this->getNamespace(Platform::isUrlAdmin()) . '::' . $view;
            $viewParts = explode('::', $view);
        }

        [$namespace, $viewName] = $viewParts;

        if (!str_starts_with($namespace, 'theme\\')) {
            $themeNamespace = $this->namespaceTheme();
            if ($noScope) {
                if (View::exists("{$themeNamespace}::{$viewName}")) {
                    return view("{$themeNamespace}::{$viewName}", $data, $mergeData);
                }
                return view($view, $data, $mergeData);
            }
            $scopeView = "{$themeNamespace}::scope.{$namespace}.{$viewName}";
            $viewWithoutScope = "{$themeNamespace}::scope.{$viewName}";


            if (View::exists($scopeView)) {
                return view($scopeView, $data, $mergeData);
            } elseif (View::exists($viewWithoutScope)) {
                return view($viewWithoutScope, $data, $mergeData);
            }
        }
        return view($view, $data, $mergeData);
    }
    public function include($view, array $data = [], array $mergeData = [])
    {
        echo $this->view($view, $data, $mergeData)->render();
    }
    public function setLayout(string $layout = 'none', $isAdmin = null)
    {
        if (!View::exists($layout)) {
            $temp = $this->getLayoutName($layout, $isAdmin);

            if (View::exists($temp)) {
                $layout = $temp;
            }
        }
        $this->layout = $layout;
    }
    public function getLayout(string $default = null): string
    {
        $arrs = [
            $default,
            $default ?  $this->getLayoutName($default) : '',
            $this->layout,
            $this->getLayoutName('default'),
        ];
        foreach ($arrs as $layout) {
            if ($layout && View::exists($layout)) {
                return $layout;
            }
        }
        return 'sokeio::layouts.none';
    }
}
