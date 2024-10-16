<?php

namespace Sokeio\Menu;

use Sokeio\HtmlBuilder;
use Illuminate\Support\Str;
use Sokeio\Facades\MenuRender;

class MenuBuilder extends HtmlBuilder
{
    public const ADMIN_BUILDER_ATTACH_MENU = 'ADMIN_BUILDER_ATTACH_MENU';
    protected $warps = [];
    protected $items = [];
    protected $sub = false;
    protected $targetId = '';
    protected static $sortNumber = 1000;
    protected $subLevel = 0;
    protected $position = [];

    protected MenuItemBuilder|null $parent;
    public function getPosition()
    {
        return $this->position;
    }
    public function getSubLevel()
    {
        return $this->subLevel;
    }
    public function __construct($position = '', $sub = false, $level = 0, $parent = null)
    {
        $this->position = $position;
        $this->sub = $sub;
        $this->subLevel = $level;
        $this->parent = $parent;
    }
    public function setTargetId($targetId): self
    {
        $this->targetId = $targetId;
        return $this;
    }
    public function checkSub()
    {
        return isset($this->sub) && $this->sub;
    }
    public function getItems()
    {
        return $this->items;
    }
    public function link($link, $text, $icon = '', $attributes = [], $per = '', $sort = 20, $afterCallback = null): self
    {
        return $this->addItem([
            MenuItemBuilder::KEY_TYPE => MenuItemBuilder::ITEM_LINK,
            MenuItemBuilder::KEY_LINK => $link,
            MenuItemBuilder::KEY_TEXT => $text,
            MenuItemBuilder::KEY_ICON => $icon,
            MenuItemBuilder::KEY_ATTRIBUTE => $attributes,
            MenuItemBuilder::KEY_PERMISSION => $per,
            MenuItemBuilder::KEY_SORT => $sort >  -1 ? $sort : (self::$sortNumber++)
        ], $afterCallback);
    }
    public function route($data, $text, $icon = '', $attributes = [], $per = '', $sort = 20, $afterCallback = null)
    {
        return $this->addItem([
            MenuItemBuilder::KEY_TYPE => MenuItemBuilder::ITEM_ROUTE,
            MenuItemBuilder::KEY_DATA => $data,
            MenuItemBuilder::KEY_TEXT => $text,
            MenuItemBuilder::KEY_ICON => $icon,
            MenuItemBuilder::KEY_ATTRIBUTE => $attributes,
            MenuItemBuilder::KEY_PERMISSION => $per,
            MenuItemBuilder::KEY_SORT => $sort >  -1 ? $sort : (self::$sortNumber++)
        ], $afterCallback);
    }
    public function button($text, $icon = '', $attributes = [], $per = '', $sort  = 20, $afterCallback = null): self
    {
        return $this->addItem([
            MenuItemBuilder::KEY_TYPE => MenuItemBuilder::ITEM_BUTTON,
            MenuItemBuilder::KEY_TEXT => $text,
            MenuItemBuilder::KEY_ICON => $icon,
            MenuItemBuilder::KEY_ATTRIBUTE => $attributes,
            MenuItemBuilder::KEY_PERMISSION => $per,
            MenuItemBuilder::KEY_SORT => $sort >  -1 ? $sort : (self::$sortNumber++)
        ], $afterCallback);
    }
    public function subMenu($text, $icon, $itemCallback, $sort  = 20, $afterCallback = null)
    {
        return $this->addItem([
            MenuItemBuilder::KEY_TYPE => MenuItemBuilder::ITEM_SUB,
            MenuItemBuilder::KEY_TEXT => $text,
            MenuItemBuilder::KEY_ICON => $icon,
            MenuItemBuilder::KEY_CALLBACK => $itemCallback,
            MenuItemBuilder::KEY_SORT => $sort >  -1 ? $sort : (self::$sortNumber++)

        ], $afterCallback);
    }
    public function addItem($data, $callback = null)
    {
        $item = new MenuItemBuilder($data, $this);
        $this->items[] = $item;
        if ($callback && is_callable($callback)) {
            call_user_func($callback, $item, $this);
        }
        return $this;
    }
    public function attachMenu($targetId, $callback): self
    {
        addAction(self::ADMIN_BUILDER_ATTACH_MENU . '_' . Str::Upper($targetId), function ($menu) use ($callback) {
            if ($callback) {
                $callback($menu);
            }
        });
        return $this;
    }
    public function wrapDiv($class, $id, $attributes = []): self
    {
        $this->warps[] = ['class' => $class, 'id' => $id, MenuItemBuilder::KEY_ATTRIBUTE => $attributes];
        return $this;
    }
    private function addItemWithDatabase($data, MenuBuilder $menu, $parentId = 0)
    {
        foreach ($data as $item) {
            if ($item['parent_id'] == $parentId) {
                $isSub = $data->where('parent_id', $item['id'])->count() > 0;
                if ($isSub) {
                    $menu->subMenu($item['name'], $item['icon'], function ($menuSub) use ($data, $item) {
                        $this->addItemWithDatabase($data, $menuSub, $item['id']);
                    }, $item['order'], function (MenuItemBuilder $menuItem) use ($item) {
                        $menuItem->setValueContentType($item['data_type']);
                        $menuItem->setValueContentData($item['data']);
                        $menuItem->setValueContentColor($item['color']);
                        $menuItem->setValueLink($item['link']);
                        $menuItem->setValueInfo($item['info']);
                        $menuItem->setValueClassName($item['class_name']);
                    });
                } else {
                    $menu->link(
                        $item['link'],
                        $item['name'],
                        $item['icon'],
                        $item['attribute'],
                        $item['permission'],
                        $item['order'],
                        function (MenuItemBuilder $menuItem) use ($item) {
                            $menuItem->setValueContentType($item['data_type']);
                            $menuItem->setValueContentData($item['data']);
                            $menuItem->setValueContentColor($item['color']);
                            $menuItem->setValueInfo($item['info']);
                            $menuItem->setValueClassName($item['class_name']);
                        }
                    ); // = new MenuItemBuilder($item, $menu);
                }
            }
        }
    }
    public function withDatabase($data)
    {
        $this->addItemWithDatabase($data, $this, 0);
        return $this;
    }
    public function checkActive()
    {
        foreach ($this->items as $item) {
            if ($item->checkActive()) {
                return true;
            }
        }
        return false;
    }
    public function checkView()
    {
        foreach ($this->items as $item) {
            if ($item->checkView()) {
                return true;
            }
        }
        return false;
    }
    public function beforeRender(): self
    {
        $this->genId('menu-');
        doAction(self::ADMIN_BUILDER_ATTACH_MENU . '_' . Str::Upper($this->targetId), $this);
        // fix sort by key
        $inventory = $this->items;
        if (usort($inventory, function (MenuItemBuilder $item1, MenuItemBuilder $item2) {
            if ($item1->getValueSort() === $item2->getValueSort()) {
                return 0;
            }
            return $item1->getValueSort() < $item2->getValueSort() ? -1 : 1;
        })) {
            $this->items = $inventory;
        }
        foreach ($this->items as $item) {
            $item->beforeRender();
        }

        return $this;
    }
    protected function render()
    {
        if (!$this->checkSub()) {
            $this->beforeRender();
        }
        if (!$this->checkView()) {
            return;
        }

        foreach ($this->warps as $item) {
            $attribute = '';
            if (isset($item[MenuItemBuilder::KEY_ATTRIBUTE])) {
                foreach ($item[MenuItemBuilder::KEY_ATTRIBUTE] as $key => $value) {
                    $attribute .= $key . "='" . urlencode(json_encode($value)) . "' ";
                }
            }
            echo "<div class='" . $item['class'] . "' id='" . $item['id'] . "' " . $attribute . " >";
        }

        MenuRender::doRender($this, $this->position);

        foreach ($this->warps as $item) {
            echo "</div>";
        }
    }
}
