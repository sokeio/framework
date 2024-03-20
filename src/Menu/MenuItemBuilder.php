<?php

namespace Sokeio\Menu;

use Illuminate\Support\Facades\Request;
use Sokeio\Facades\MenuRender;
use Sokeio\HtmlBuilder;

class MenuItemBuilder extends HtmlBuilder
{
    use WithMenuItemProperty;

    public const ITEM_LINK = 'ITEM_LINK';
    public const ITEM_ROUTE = 'ITEM_ROUTE';
    public const ITEM_BUTTON = 'ITEM_BUTTON';
    public const ITEM_SUB = 'ITEM_SUB';
    protected MenuBuilder $parent;

    public function __construct($data, MenuBuilder $parent)
    {
        $this->dataItem = $data;
        $this->parent = $parent;
    }
    protected $dataItem = [];
    protected $subMenu = null;
    protected function getValue($key, $default = null)
    {
        return data_get($this->dataItem, $key, $default);
    }
    protected function setValue($key, $value, $default = null)
    {
        data_set($this->dataItem, $key, $value, $default);
        return $this;
    }
    public function checkActive()
    {
        if ($this->getValueType() === self::ITEM_SUB) {
            return $this->subMenu->checkActive();
        }
        return Request::url() === $this->getValueLink();
    }
    public function checkView()
    {
        if ($this->getValueType() === self::ITEM_SUB) {
            return $this->subMenu->checkView();
        }
        $per = $this->getPermission();
        if (!$per) {
            return true;
        }
        return checkPermission($per);
    }
    public function checkSubMenu()
    {
        return isset($this->subMenu) && $this->subMenu != null;
    }
    public function getSubMenu(): MenuBuilder
    {
        return $this->subMenu;
    }
    public function getParent(): MenuBuilder
    {
        return $this->parent;
    }
    public function beforeRender()
    {
        $this->genId('menu-item-');
        if ($this->getValueType() === self::ITEM_SUB) {
            $callback = $this->getValueCallback();
            if ($callback && is_callable($callback)) {
                $levelNext = $this->parent->getSubLevel() + 1;
                $this->subMenu = new MenuBuilder($this->parent->getPosition(), true, $levelNext, $this);
                $callback($this->subMenu);
                $this->subMenu->beforeRender();
            }
        } elseif ($this->getValueType() === self::ITEM_ROUTE) {
            $params = [];
            $name = $this->getValueData();
            if (is_array($name)) {
                ['name' => $name, 'params' => $params] = $name;
            }
            $this->dataItem[self::KEY_LINK] = route($name, $params);
        } else {
            $link = $this->getValueLink();
            if (!$link) {
                return;
            }

            if (is_callable($link)) {
                $this->dataItem[self::KEY_LINK] = $link($this);
            } elseif (is_array($link)) {
                ['name' => $route, 'params' => $params] = $link;
                $this->dataItem[self::KEY_LINK] = route($route, $params);
            }
        }
    }
    protected function render()
    {
        MenuRender::doRenderItem($this, $this->parent->getPosition());
    }
}
