<?php

namespace Sokeio\Menu;


class MenuManager
{
    private static $menus = [];
    public static function admin(): self
    {
        return static::$menus['admin'] ??= new static(true);
    }

    public static function site(): self
    {
        return static::$menus['site'] ??= new static(false);
    }
    public static function registerItem($item)
    {
        return static::admin()->register($item);
    }
    public static function renderMenu($isAdmin = true)
    {
        if ($isAdmin) {
            return static::admin()->render();
        }
        return static::site()->render();
    }
    private function __construct(private $isAdmin = false) {}
    private $menuItems = [];
    public function register($menu)
    {
        $this->menuItems[] = $menu;
    }
    private function beforRender()
    {
        return collect($this->menuItems)->each(function ($item) {
            $item->setManager($this);
        });
    }
    private function render()
    {
        $items =  $this->beforRender();
    }
}
