<?php

namespace Sokeio\Theme;

use Illuminate\Support\Facades\View;
use Sokeio\Platform;

class ThemeManager
{
    private $headBefore = [];
    private $headAfter = [];
    private $bodyBefore = [];
    private $bodyAfter = [];
    private $useCdn = false;
    private $title = '';
    private $description = '';
    private $keywords = '';
    private $arrJavascript = [];
    private $arrStyle = [];
    private $arrLinkCss = [];
    private $arrLinkJs = [];
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
    public function getSiteInfo()
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'keywords' => $this->keywords,
        ];
    }
    public function title($title)
    {
        $this->title = $title;
        return $this;
    }
    public function description($description)
    {
        $this->description = $description;
        return $this;
    }
    public function keywords($keywords)
    {
        $this->keywords = $keywords;
        return $this;
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
    public function jsFromPath($path)
    {
        if (file_exists($path)) {
            return $this->js(file_get_contents($path));
        }
        return $this;
    }
    public function js($content)
    {
        $content = trim($content);
        $key = md5($content);
        $this->arrJavascript[$key] = $content;
        return $this;
    }
    public function styleFromPath($path)
    {
        if (file_exists($path)) {
            return $this->style(file_get_contents($path));
        }
        return $this;
    }
    public function style($content)
    {
        $content = trim($content);
        $key = md5($content);
        $this->arrStyle[$key] = $content;
        return $this;
    }
    public function linkCss($link, $cdn = null)
    {
        $this->arrLinkCss[trim($link)] = $cdn;
        return $this;
    }
    public function linkJs($link, $cdn = null)
    {
        $this->arrLinkJs[trim($link)] = $cdn;
        return $this;
    }
    private function linkRender($link)
    {
        if (!$link) {
            return;
        }
        echo '<link rel="stylesheet"  href="' . ($link) . '"/>';
    }
    public function headRender()
    {
        if ($this->title) {
            echo '<title>' . $this->title . '</title>';
        }
        if ($this->description) {
            echo '<meta name="description" content="' . $this->description . '">';
        }
        if ($this->keywords) {
            echo '<meta name="keywords" content="' . $this->keywords . '">';
        }
        foreach ($this->headBefore as $callback) {
            $callback();
        }
        foreach ($this->arrLinkCss as $link => $cdn) {
            if ($cdn && $this->useCdn || !$link) {
                $this->linkRender($cdn);
            } else {
                $this->linkRender($link);
            }
        }

        foreach ($this->arrStyle as $key => $value) {
            echo '<style type="text/css" id="' . $key . '">' . $value . '</style>';
        }


        foreach ($this->headAfter as $callback) {
            $callback();
        }
    }
    public function bodyRender()
    {
        foreach ($this->bodyBefore as $callback) {
            $callback();
        }
    }

    private function jsRender($script)
    {
        if (!$script) {
            return;
        }
        echo '<script type="text/javascript" id="' . md5($script) . '" src="' . ($script) . '"></script>';
    }
    public function bodyEndRender()
    {
        foreach ($this->arrLinkJs as $link => $cdn) {
            if ($cdn && $this->useCdn || !$link) {
                $this->jsRender($cdn);
            } else {
                $this->jsRender($link);
            }
        }
        foreach ($this->arrJavascript as $key => $value) {
            echo '<script type="text/javascript" id="' . $key . '">' . $value . '</script>';
        }

        foreach ($this->bodyAfter as $callback) {
            $callback();
        }
    }
    public function bodyBefore($callback)
    {
        if (!is_callable($callback)) {
            return;
        }
        $this->bodyBefore[] = $callback;
    }
    public function bodyAfter($callback)
    {
        if (!is_callable($callback)) {
            return;
        }
        $this->bodyAfter[] = $callback;
    }
    public function headBefore($callback)
    {
        if (!is_callable($callback)) {
            return;
        }
        $this->headBefore[] = $callback;
    }
    public function headAfter($callback)
    {
        if (!is_callable($callback)) {
            return;
        }
        $this->headAfter[] = $callback;
    }
    public function getNamespace($isAdmin = false)
    {
        return $isAdmin ? 'theme\\admin' : 'theme\\site';
    }
    public function namespaceTheme()
    {
        return $this->getNamespace(Platform::isUrlAdmin());
    }
    public function view(string $view, array $data = [], array $mergeData = [])
    {
        $viewParts = explode('::', $view);

        if (count($viewParts) === 1) {
            $view = $this->getNamespace(Platform::isUrlAdmin()) . '::' . $view;
            $viewParts = explode('::', $view);
        }

        [$namespace, $viewName] = $viewParts;

        if (!str_starts_with($namespace, 'theme\\')) {
            $themeNamespace = $this->namespaceTheme();
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
    public function getLayout(string $default = null): string
    {
        $arrs = [
            $this->layout,
            $this->namespaceTheme() . '::layouts.default',
            $default
        ];
        foreach ($arrs as $layout) {
            if ($layout && View::exists($layout)) {
                return $layout;
            }
        }


        return 'sokeio::layouts.none';
    }
}
