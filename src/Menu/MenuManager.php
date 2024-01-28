<?php

namespace Sokeio\Menu;

use Illuminate\Support\Facades\Route;

class MenuManager
{
    private $registers = [];
    private $positions = [];
    private $default = 'sidebar';
    public function getDefault()
    {
        return $this->default;
    }
    public function switchDefault($default)
    {
        $this->default = $default;
    }
    public function position($_position = ''): MenuBuilder
    {
        if (!$_position) $_position = $this->default;
        if (!isset($this->positions[$_position]) || !$this->positions[$_position]) {
            $this->positions[$_position] = new MenuBuilder($_position);
        }
        return $this->positions[$_position];
    }
    public function render($_position = '')
    {
        return $this->position($_position)->toHtml();
    }
    public function link($link, $text, $icon = '', $attributes = [], $per = '', $sort = 20, $_position = ''): MenuBuilder
    {
        return $this->position($_position)->link($link, $text, $icon, $attributes, $per, $sort);
    }
    public function route($data, $text, $icon = '', $attributes = [], $per = '', $sort = 20, $_position = ''): MenuBuilder
    {
        return $this->position($_position)->route($data, $text, $icon, $attributes, $per, $sort);
    }
    public function component($data, $text, $icon = '', $attributes = [], $per = '', $sort = 20, $_position = ''): MenuBuilder
    {
        return $this->position($_position)->component($data, $text, $icon, $attributes, $per, $sort);
    }
    public function action($data, $text, $icon = '', $attributes = [], $per = '', $sort = 20, $_position = ''): MenuBuilder
    {
        return $this->position($_position)->action($data, $text, $icon, $attributes, $per, $sort);
    }
    public function div($text = '', $icon = '', $attributes = [], $per = '', $sort  = 20, $_position = ''): MenuBuilder
    {
        return $this->position($_position)->div($text, $icon, $attributes, $per, $sort);
    }
    public function tag($tag, $text, $icon = '', $attributes = [], $per = '', $sort  = 20, $_position = ''): MenuBuilder
    {
        return $this->position($_position)->tag($tag, $text, $icon, $attributes, $per, $sort);
    }
    public function button($text, $icon = '', $attributes = [], $per = '', $sort  = 20, $_position = ''): MenuBuilder
    {
        return $this->position($_position)->button($text, $icon, $attributes, $per, $sort);
    }
    public function subMenu($text, $icon = '', $callback, $sort  = 20, $_position = ''): MenuBuilder
    {
        return $this->position($_position)->subMenu($text, $icon, $callback, $sort);
    }
    public function attachMenu($targetId, $callback, $_position = ''): MenuBuilder
    {
        return $this->position($_position)->attachMenu($targetId, $callback);
    }
    public function wrapDiv($class, $id, $attributes = [], $_position = ''): MenuBuilder
    {
        return $this->position($_position)->wrapDiv($class, $id, $attributes);
    }
    public function withDatabase($data, $_position = '')
    {
        return $this->position($_position)->withDatabase($data);
    }

    public function register($callback)
    {
        if ($callback && is_callable($callback)) {
            $this->registers[] = $callback;
        }
    }
    public function doRegister()
    {
        if (!Route::current()) return;
        foreach ($this->registers as $callback) {
            $callback();
        }
        $this->registers = [];
    }
}
