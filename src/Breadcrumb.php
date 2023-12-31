<?php

namespace Sokeio;

use Sokeio\Laravel\BaseCallback;

class Breadcrumb extends BaseCallback
{
    private static $data = [];
    public static function Make($key = '')
    {
        if ($key == '') $key = 'base';
        return isset(self::$data[$key]) ? self::$data[$key] : (self::$data[$key] = new static());
    }
    public static function ClearBreadcrumb($key = '')
    {
        if ($key == '') $key = 'base';
        if (isset(self::$data[$key])) unset(self::$data[$key]);
    }
    public static function Item($text, $link = '', $class = '')
    {
        return ['text' => $text, 'link' => $link, 'class' => $class];
    }
    private $breadcrumb = [];
    private $title = '';
    public function Breadcrumb($breadcrumb)
    {
        $this->breadcrumb = $breadcrumb ?? [];
        return $this;
    }
    public function Add($text, $link = '', $class = '')
    {
        $this->breadcrumb[] = ['text' => $text, 'link' => $link, 'class' => $class];
        return $this;
    }
    public function Title($title)
    {
        $this->title = $title;
        return $this;
    }
    public function getTitle()
    {
        return $this->title ?? '';
    }
    public function getBreadcrumb()
    {
        return $this->breadcrumb ?? [];
    }
    public function Render()
    {
        return view('sokeio::breadcrumb', [
            'title' => $this->getTitle(),
            'breadcrumb' => $this->getBreadcrumb()
        ])->render();
    }
    public function __invoke()
    {
        return $this->Render();
    }
    public function __toString()
    {
        return $this->Render();
    }
}
