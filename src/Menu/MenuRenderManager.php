<?php

namespace Sokeio\Menu;

use Livewire\Livewire;

class MenuRenderManager
{
    public function __construct()
    {
        $this->renderCallback(function (MenuBuilder $item) {
            echo '<ul>';
            foreach ($item->getItems() as $_item) {
                echo $_item->toHtml();
            }
            echo '</ul>';
        });
        $this->renderItemCallback(function (MenuItemBuilder $item) {
            echo '<li>';
            echo $item->getValueText();
            if ($item->checkSubMenu()) {
                echo $item->getSubMenu()->toHtml();
            }
            echo '</li>';
        });
    }

    private $callback;
    private $itemCallback;
    private $menuTypes = [];
    public function registerType($menuType)
    {
        $type = ($menuType)::getMenuType();
        $title = ($menuType)::getMenuName();
        $this->menuTypes[$type] = [
            'type' => $type,
            'title' => $title,
            'setting' => $menuType
        ];
    }
    public function getMenuType()
    {
        return collect($this->menuTypes);
    }
    public function renderMenuSetting($menu)
    {
        if (isset($this->menuTypes[$menu['data_type']]['setting'])) {
            $html = Livewire::mount($this->menuTypes[$menu['data_type']]['setting'], ['dataId' => $menu->id]);
            return '<div class="p-4">' .  $html . '</div>';
        }
        return '<div class="p-4">Not found</div>';
    }
    public function renderCallback($callback)
    {
        $this->callback = $callback;
        return $this;
    }
    public function renderItemCallback($callback)
    {
        $this->itemCallback = $callback;
        return $this;
    }
    public function doRender($item, $_position = '')
    {
        if ($this->callback && is_callable($this->callback)) {
            call_user_func($this->callback, $item, $_position);
        }
        return $this;
    }
    public function doRenderItem($item, $_position = '')
    {
        $type = $item->getValueContentType();
        if ($type && isset($this->menuTypes[$type])) {
            ($this->menuTypes[$type]['setting'])::renderItem($item, $_position);

            return $this;
        }

        if ($this->itemCallback && is_callable($this->itemCallback)) {
            call_user_func($this->itemCallback, $item, $_position);
        }
        return $this;
    }
}
