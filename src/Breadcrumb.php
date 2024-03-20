<?php

namespace Sokeio;

use Sokeio\Laravel\BaseCallback;

class Breadcrumb extends BaseCallback
{
    private static $data = [];
    public static function make($key = '')
    {
        if ($key === '') {
            $key = 'base';
        }
        return isset(self::$data[$key]) ? self::$data[$key] : (self::$data[$key] = new static());
    }
    public static function clearByKey($key = '')
    {
        if ($key === '') {
            $key = 'base';
        }
        if (isset(self::$data[$key])) {
            unset(self::$data[$key]);
        }
    }
    public static function item($text, $link = '', $class = '')
    {
        return ['text' => $text, 'link' => $link, 'class' => $class];
    }
    private $breadcrumb = [];
    private $title = '';
    private $classBox = '';
    public function breadcrumb($breadcrumb)
    {
        $this->breadcrumb = $breadcrumb ?? [];
        return $this;
    }
    public function add($text, $link = '', $class = '')
    {
        $this->breadcrumb[] = ['text' => $text, 'link' => $link, 'class' => $class];
        return $this;
    }
    public function exists()
    {
        return count($this->breadcrumb) > 0;
    }
    public function classBox($classBox)
    {
        $this->classBox = $classBox;
        return $this;
    }
    public function getclassBox()
    {
        return $this->classBox ?? '';
    }
    public function title($title)
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
    public function render()
    {
        return view('sokeio::breadcrumb', [
            'title' => $this->getTitle(),
            'classBox' => $this->getclassBox(),
            'breadcrumb' => $this->getBreadcrumb()
        ])->render();
    }
    public function __invoke()
    {
        return $this->render();
    }
    public function __toString()
    {
        return $this->render();
    }
}
