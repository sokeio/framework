<?php

namespace Sokeio\Menu;

use Illuminate\Contracts\Support\Arrayable;
use Sokeio\Theme;

class MenuItem implements Arrayable
{
    public static function make($key, $title, $url = null, $icon = null, $target = null)
    {
        return new static($key, $title, $url, $icon, $target);
    }
    protected MenuManager $menuManager;
    public $classItem;
    private $route;
    public $position = 'default';
    public $badge = null;
    public $sort = 0;
    public function __construct(
        public $key,
        public $title,
        public $url = null,
        public $icon = null,
        public $target = null,
    ) {}
    public function route(string|callable|array $route)
    {
        $this->route = $route;
        return $this;
    }
    public function setup(callable $callback)
    {
        $callback($this);
        return $this;
    }
    public function url(): string
    {
        if ($this->url === null && $this->route !== null) {
            $route = $this->route;

            if (is_callable($route)) {
                $route = $route($this);
            }

            if (is_string($route)) {
                $this->url = $route === $this->route
                    ? route($route)
                    : $route;
            } elseif (is_array($route)) {
                [$name, $params] = $route;
                $this->url = route($name, $params);
            }
        }

        return $this->url;
    }
    public function icon()
    {
        if ($this->icon === null || $this->icon === '') {
            return '';
        }
        if ($this->icon != strip_tags($this->icon)) {
            return $this->icon;
        }
        return '<i class="' . $this->icon . '"></i>';
    }

    public function setManager(MenuManager $menuManager)
    {
        $this->menuManager = $menuManager;
    }
    public function children()
    {
        return $this->menuManager->getItems()->where(function (MenuItem $item) {
            return $item->target == $this->key && $item->position == $this->position;
        });
    }
    public function render($level = 0)
    {
        if ($level > 0) {
            return Theme::view('partials.menu.dropdown-item', ['item' => $this, 'level' => $level])->render();
        }

        return Theme::view('partials.menu.item', ['item' => $this, 'level' => $level])->render();
    }

    public function toArray()
    {
        return [
            'key' => $this->key,
            'title' => $this->title,
            'url' => $this->url(),
            'icon' => $this->icon(),
            'target' => $this->target,
            'position' => $this->position,
            'badge' => $this->badge,
            'sort' => $this->sort,
        ];
    }
}
