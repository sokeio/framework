<?php

namespace Sokeio\Theme;

use Illuminate\Support\Facades\View;
use Sokeio\Platform;
use Sokeio\Theme\Concerns\ThemeData;
use Sokeio\Theme\Concerns\ThemeHook;
use Sokeio\Theme\Concerns\ThemeLocation;
use Sokeio\Theme\Concerns\ThemeMeta;
use Sokeio\Tailwindcss;

class ThemeManager
{
    use ThemeHook, ThemeData, ThemeMeta;
    use ThemeLocation;
    private $useCdn = false;
    private $layout;
    private $themeSite = null;
    private $themeAdmin = null;

    protected function getThemeSiteKey()
    {
        return "SOKEIO_THEME_SITE_OPTION_" . md5($this->getThemeSite()->id);
    }
    public function setOptions($options)
    {
        setting()->set($this->getThemeSiteKey(), $options)->save();
        return $this;
    }
    public function option($key = null, $default = null)
    {
        if (!$key) {
            return setting($this->getThemeSiteKey());
        }
        return data_get(setting($this->getThemeSiteKey()), $key, $default);
    }
    public function getTemplates()
    {
        return $this->getThemeSite()->templates ?? [];
    }
    public function getLocations()
    {
        return $this->getThemeSite()->locations ?? [];
    }
    public function getLocationOptions($target = null)
    {
        return collect($this->getLocations())->filter(function ($item) use ($target) {
            return !$target || in_array($target, data_get($item, 'target', []));
        })->map(function ($item, $key) {
            return ['value' => $key, 'text' => data_get($item, 'name', $key)];
        })->toArray();
    }
    public function getTemplateOptions($target = null)
    {
        return collect($this->getTemplates())->filter(function ($item) use ($target) {
            return !$target || in_array($target, data_get($item, 'target', []));
        })->map(function ($item, $key) {
            return ['value' => $key, 'text' => data_get($item, 'name', $key)];
        })->toArray();
    }
    public function getTemplate($template, $key = null)
    {

        if ($key) {
            return data_get($this->getTemplates()[$template], $key);
        }
        return $this->getTemplates()[$template];
    }
    public function viewTemplate($template, $data = [], $mergeData = [], $noScope = false, $view = null)
    {
        $temp = $this->getTemplate($template);
        if (!$temp) {
            return $this->view($view, $data, $mergeData, $noScope);
        }
        if ($temp['layout']) {
            $this->setLayout($temp['layout']);
        }
        return $this->view($temp['view'], $data, $mergeData, $noScope);
    }

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
        Tailwindcss::render($this->getTheme());
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
        $tempView = $view;
        if (!str_starts_with($namespace, 'theme\\')) {
            $themeNamespace = $this->namespaceTheme();
            if ($noScope) {
                if (View::exists("{$themeNamespace}::{$viewName}")) {
                    $tempView = "{$themeNamespace}::{$viewName}";
                }
            } else {
                $scopeView = "{$themeNamespace}::scope.{$namespace}.{$viewName}";
                $viewWithoutScope = "{$themeNamespace}::scope.{$viewName}";
                if (View::exists($scopeView)) {
                    $tempView = $scopeView;
                } elseif (View::exists($viewWithoutScope)) {
                    $tempView = $viewWithoutScope;
                }
            }
        }
        return view($tempView, $data, $mergeData);
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
            // $this->getLayoutName('default'),
        ];
        foreach ($arrs as $layout) {
            if ($layout && View::exists($layout)) {
                return $layout;
            }
        }
        $layoutDefault = $this->getLayoutName('default');
        if (Platform::isUrlAdmin()) {
            $layoutDefault = $this->getLayoutName(setting('SOKEIO_LAYOUT_ADMIN_THEME', 'default'), true);
        }
        if (View::exists($layoutDefault)) {
            return $layoutDefault;
        }
        return 'sokeio::layouts.none';
    }
}
