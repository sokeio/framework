<?php

namespace Sokeio\Support\Menu;

use Illuminate\Contracts\Support\Arrayable;
use Sokeio\Theme;

class MenuManager implements Arrayable
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
    /**
     * Render menu by given position and whether it is for admin.
     *
     * @param string $position
     * @param bool $isAdmin
     * @return string
     */

    public static function renderMenu($position = 'default', $isAdmin = true)
    {
        if ($isAdmin) {
            return static::admin()->render($position);
        }
        return static::site()->render($position);
    }
    private function __construct(private $isAdmin = false)
    {
        $this->menuItems = collect([]);
    }
    private $menuItems;
    private $isBeforeRender = false;
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
    private function beforRender()
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
            if ($item->target == null || $item->target == '') {
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
    }
    private function getItemsByPosition($position)
    {
        return $this->menuItems->where('position', $position)->where(function ($item) {
            return $item->target == '' || $item->target == null;
        })->sortBy('sort');
    }
    private function render($position = 'default')
    {
        $this->beforRender();
        return Theme::view('partials.menu.index', [
            'items' => $this->getItemsByPosition($position),
            'position' => $position
        ])->render();
    }

    public function toArray()
    {
        $this->beforRender();
        return [
            'isAdmin' => $this->isAdmin,
            'menus' => $this->menuItems->toArray()
        ];
    }
}
