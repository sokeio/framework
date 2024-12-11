<?php

namespace Sokeio\Support\Menu;

use Sokeio\Theme;

class MenuPosition
{
    private function __construct()
    {
        $this->menuItems = collect([]);
    }
    private $menuItems;
    private $isBeforeRender = false;
    private $hookTarget = [];
    public function target($target, $callback)
    {
        $this->hookTarget[] = function () use ($target, $callback) {
            $item = $this->getItemsByKey($target);
            if ($item->count() > 0) {
                $item = $item->first();
                $callback($item);
            }
        };
    }
    public function register($menu)
    {
        $this->menuItems->push($menu);
        $this->isBeforeRender = false;
        return $this;
    }
    public function getItems()
    {
        return $this->menuItems;
    }
    private function beforeRender()
    {
        if ($this->isBeforeRender) {
            return;
        }
        $this->isBeforeRender = true;
        $this->menuItems->each(function ($item) {
            $item->setManager($this);
        });
        $items = $this->menuItems;
        foreach ($this->menuItems as $item) {
            if ($item->target == '') {
                continue;
            }
            $arr = str($item->target)->split('/\./');
            if (count($arr) < 2) {
                continue;
            }
            if ($items->where('key', $item->target)->count() > 0) {
                continue;
            }
            $len = count($arr) - 1;
            for ($i = 0; $i < $len; $i++) {
                $key = '';
                $key2 = '';
                for ($ki = 0; $ki < $len - $i + 1; $ki++) {
                    $key .= $arr[$ki] . '.';
                    if ($ki < $len - $i - 1) {
                        $key2 .= $arr[$ki] . '.';
                    }
                }
                $key = rtrim($key, '.');
                $key2 = rtrim($key2, '.');
                $items->push(MenuItem::make($key, str($arr[$len - $i])->title()->replace('-', ' '), '#')
                    ->setup(function (MenuItem $item) use ($key2) {
                        if ($key2) {
                            $item->target = $key2;
                        }
                        $item->setManager($this);
                    }));
            }
        }
        $this->menuItems = $items;

        foreach ($this->hookTarget as $callback) {
            if (!is_callable($callback)) {
                continue;
            }
            $callback();
        }
    }
    private function getItemsByKey($key)
    {
        return $this->menuItems->where('key', $key);
    }
    private function getItemsByPosition($position)
    {
        return $this->menuItems->where('position', $position)->where(function ($item) {
            return $item->target == '';
        })->sortBy('sort');
    }
    private function render($position = 'default')
    {
        $this->beforeRender();
        return Theme::view('sokeio::partials.menu.index', [
            'items' => $this->getItemsByPosition($position),
            'position' => $position
        ], [], true)->render();
    }

    public function toArray()
    {
        $this->beforeRender();
        return [
            'isAdmin' => $this->isAdmin,
            'menus' => $this->menuItems->toArray()
        ];
    }
}
