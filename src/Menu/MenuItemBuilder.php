<?php

namespace Sokeio\Menu;

use Sokeio\Facades\MenuRender;
use Sokeio\HtmlBuilder;

class MenuItemBuilder extends HtmlBuilder
{
    public const ITEM_DIV = 'ITEM_DIV';
    public const ITEM_TAG = 'ITEM_TAG';
    public const ITEM_LINK = 'ITEM_LINK';
    public const ITEM_ROUTE = 'ITEM_ROUTE';
    public const ITEM_COMPONENT = 'ITEM_COMPONENT';
    public const ITEM_ACTION = 'ITEM_ACTION';
    public const ITEM_BUTTON = 'ITEM_BUTTON';
    public const ITEM_SUB = 'ITEM_SUB';

    public const KEY_CONTENT_TYPE = 'KEY_CONTENT_TYPE';
    public const KEY_CONTENT_COLOR = 'KEY_CONTENT_COLOR';
    public const KEY_CONTENT_DATA = 'KEY_CONTENT_DATA';
    public const KEY_TYPE = 'KEY_TYPE';
    public const KEY_TEXT = 'KEY_TEXT';
    public const KEY_INFO = 'KEY_INFO';
    public const KEY_ICON = 'KEY_ICON';
    public const KEY_ATTRIBUTE = 'KEY_ATTRIBUTE';
    public const KEY_SORT = 'KEY_SORT';
    public const KEY_TAG = 'KEY_TAG';
    public const KEY_LINK = 'KEY_LINK';
    public const KEY_DATA = 'KEY_DATA';
    public const KEY_CALLBACK = 'KEY_CALLBACK';
    public const KEY_BADGE = 'KEY_BADGE';
    public const KEY_PERMISSION = 'KEY_PERMISSION';

    protected MenuBuilder $parent;

    public function __construct($data = [], MenuBuilder $parent)
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
    public function getValueContentData()
    {
        return $this->getValue(self::KEY_CONTENT_DATA);
    }

    public function setValueContentData($value, $default = null)
    {
        return $this->setValue(self::KEY_CONTENT_DATA, $value, $default);
    }
    public function getValueContentType()
    {
        return $this->getValue(self::KEY_CONTENT_TYPE);
    }
    public function setValueContentType($value, $default = null)
    {
        return $this->setValue(self::KEY_CONTENT_TYPE, $value, $default);
    }
    public function getValueInfo()
    {
        return $this->getValue(self::KEY_INFO);
    }
    public function setValueInfo($value, $default = null)
    {
        return $this->setValue(self::KEY_INFO, $value, $default);
    }
    public function getValueContentColor()
    {
        return $this->getValue(self::KEY_CONTENT_COLOR);
    }
    public function setValueContentColor($value, $default = null)
    {
        return $this->setValue(self::KEY_CONTENT_COLOR, $value, $default);
    }
    public function getValueType()
    {
        return $this->getValue(self::KEY_TYPE);
    }
    public function getPermission()
    {
        return $this->getValue(self::KEY_PERMISSION);
    }
    public function getValueText()
    {
        return $this->getValue(self::KEY_TEXT);
    }
    public function getValueIcon()
    {
        return $this->getValue(self::KEY_ICON);
    }
    public function getValueAttribute()
    {
        return $this->getValue(self::KEY_ATTRIBUTE);
    }
    public function getValueSort()
    {
        return $this->getValue(self::KEY_SORT);
    }
    public function getValueTag()
    {
        return $this->getValue(self::KEY_TAG);
    }
    public function getValueLink()
    {
        return $this->getValue(self::KEY_LINK);
    }
    public function setValueLink($value, $default = null)
    {
        return $this->setValue(self::KEY_LINK, $value, $default);
    }
    public function getValueData()
    {
        return $this->getValue(self::KEY_DATA);
    }
    public function getValueCallback()
    {
        return $this->getValue(self::KEY_CALLBACK);
    }
    public function checkActive()
    {
        if ($this->getValueType() === self::ITEM_SUB) {
            return $this->subMenu->checkActive();
        }
        return MenuBuilder::checkUrl($this->getValueLink());
    }
    public function checkView()
    {
        if ($this->getValueType() === self::ITEM_SUB) {
            return $this->subMenu->checkView();
        }
        $per = $this->getPermission();
        if (!$per) return true;
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
                $this->subMenu = new MenuBuilder($this->parent->getPosition(), true, $this->parent->getSubLevel() + 1, $this);
                $callback($this->subMenu);
                $this->subMenu->beforeRender();
            }
        } else   if ($this->getValueType() === self::ITEM_ACTION) {
            $params = [];
            $name = $this->getValueData();
            if (is_array($name)) {
                ['name' => $name, 'params' => $params] = $name;
            }
            $this->dataItem[self::KEY_LINK] = sokeio_action($name, $params);
        } else   if ($this->getValueType() === self::ITEM_ROUTE) {
            $params = [];
            $name = $this->getValueData();
            if (is_array($name)) {
                ['name' => $name, 'params' => $params] = $name;
            }
            $this->dataItem[self::KEY_LINK] = route($name, $params);
        } else   if ($this->getValueType() === self::ITEM_COMPONENT) {
            $params = [];
            $name = $this->getValueData();
            if (is_array($name)) {
                ['name' => $name, 'params' => $params] = $name;
            }
            $this->dataItem[self::KEY_LINK] = sokeio_component($name, $params, 'livewire');
        } else {
            $link = $this->getValueLink();
            if (!$link) return;

            if (is_callable($link)) {
                $this->dataItem[self::KEY_LINK] = $link($this);
            } else if (is_array($link)) {
                ['name' => $route, 'params' => $params] = $link;
                $this->dataItem[self::KEY_LINK] = route($route, $params);
            }
        }
    }
    protected function render()
    {
        MenuRender::DoRenderItem($this, $this->parent->getPosition());
    }
}
