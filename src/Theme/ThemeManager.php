<?php

namespace Sokeio\Theme;


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
    public function title($title)
    {
        $this->title = $title;
    }
    public function description($description)
    {
        $this->description = $description;
    }
    public function keywords($keywords)
    {
        $this->keywords = $keywords;
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
    public function js($content)
    {
        $content = trim($content);
        $key = md5($content);
        $this->arrJavascript[$key] = $content;
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
        echo '<link rel="stylesheet" href="' . ($link) . '">';
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

        foreach ($this->arrLinkCss as $link => $cdn) {
            if ($cdn && $this->useCdn) {
                $this->linkRender($cdn);
            } else {
                $this->linkRender($link);
            }
        }

        foreach ($this->arrStyle as $key => $value) {
            echo '<style type="text/css" id="' . $key . '">' . $value . '</style>';
        }

        foreach ($this->headBefore as $callback) {
            $callback();
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
        echo '<script type="text/javascript" src="' . ($script) . '"></script>';
    }
    public function bodyEndRender()
    {
        foreach ($this->arrLinkJs as $link => $cdn) {
            if ($cdn && $this->useCdn) {
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
}
