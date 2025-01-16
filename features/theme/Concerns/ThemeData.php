<?php

namespace Sokeio\Theme\Concerns;

trait ThemeData
{
    private $arrData = [];
    protected function arrData($key, $isContent, $contentOrLink, $tag = 'template', $cdn = null, $id = null)
    {
        if (!isset($this->arrData[$key])) {
            $this->arrData[$key] = [];
        }
        $this->arrData[$key][] = [
            'isContent' =>
            $isContent,
            'contentOrLink' => $contentOrLink,
            'cdn' => $cdn,
            'id' => $id,
            'tag' => $tag
        ];
        return $this;
    }
    protected function renderData($key, $callback)
    {
        if (!isset($this->arrData[$key])) {
            return;
        }
        foreach ($this->arrData[$key] as $value) {
            call_user_func($callback, $value);
        }
    }
    public function templateFromPath($path, $id = null)
    {
        if (file_exists($path)) {
            return $this->template(file_get_contents($path), $id);
        }
        return $this;
    }
    public function template($content, $id = null)
    {
        return $this->arrData('template', true, $content, 'template', null, $id);
    }
    public function jsFromPath($path, $id = null)
    {
        if (file_exists($path)) {
            return $this->js(file_get_contents($path), $id);
        }
        return $this;
    }
    public function js($content, $id = null)
    {
        return $this->arrData('js', true, $content, 'script', null, $id);
    }
    public function styleFromPath($path, $id = null)
    {
        if (file_exists($path)) {
            return $this->style(file_get_contents($path), $id);
        }
        return $this;
    }
    public function style($content, $id = null)
    {
        return $this->arrData('style', true, $content, 'style', null, $id);
    }
    public function linkCss($link, $cdn = null)
    {
        return $this->arrData('linkCss', true, $link, 'link', $cdn);
    }
    public function linkJs($link, $cdn = null)
    {
        return $this->arrData('linkJs', true, $link, 'script', $cdn);
    }
}
