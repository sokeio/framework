<?php

namespace Sokeio\Menu;

use Sokeio\Theme;

class MenuCollection
{
    public function __construct(protected MenuManager $menuManager)
    {
        $this->menuItems = collect([]);
    }
    private $view = 'sokeio::partials.menu.index';
    private $viewItem0 = 'sokeio::partials.menu.item';
    private $viewItem = 'sokeio::partials.menu.dropdown-item';

    private $menuItems;
    private $isBeforeRender = false;
    private $hookTarget = [];
    public function getViewItem($level = 0)
    {
        if ($level == 0) {
            return $this->viewItem0;
        }
        return $this->viewItem;
    }
    public function target($target, $callback)
    {
        $this->hookTarget[] = function () use ($target, $callback) {
            $item = $this->menuItems->where('key', $target);
            if ($item->count() > 0) {
                $item = $item->first();
                $callback($item);
            }
        };
    }

    public function register(MenuItem $menuItem)
    {
        if ($menuItem) {
            $menuItem->setManager($this);
            $this->menuItems->push($menuItem);
        }
        $this->isBeforeRender = false;
        return $this;
    }
    public function getItems()
    {
        return $this->menuItems;
    }
    private function genItem($items, $arr)
    {
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
            $item = MenuItem::make($key, str($arr[$len - $i])->title()->replace('-', ' '), '#')
                ->tap(function (MenuItem $item) use ($key2) {
                    if ($key2) {
                        $item->target = $key2;
                    }
                });
            $item->setManager($this);
            $items->push($item);
        }
    }
    private function beforeRender()
    {
        if ($this->isBeforeRender) {
            return;
        }
        $this->isBeforeRender = true;
        $items = $this->menuItems;
        foreach ($this->menuItems as $item) {
            $arr = str($item->target ?? '')->split('/\./');

            if (count($arr) < 2 || $items->where('key', $item->target)->count() > 0) {
                continue;
            }
            $this->genItem($items, $arr);
        }
        $this->menuItems = $items;

        foreach ($this->hookTarget as $callback) {
            if (!is_callable($callback)) {
                continue;
            }
            $callback();
        }
    }
    public function getItemsByTarget($target)
    {
        return $this->menuItems->where(function ($item) use ($target) {
            return $item->target == $target;
        })->sortBy('sort');
    }
    public function render()
    {
        $this->beforeRender();
        return Theme::view($this->view, [
            'items' => $this->getItemsByTarget('')
        ], [], true)->render();
    }

    public function toArray()
    {
        $this->beforeRender();
        return [
            'menus' => $this->menuItems->toArray()
        ];
    }
}
