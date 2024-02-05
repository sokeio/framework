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

    private $Callback = [];
    private $ItemCallback = [];
    private $menuTypes = [];
    public function RegisterType($menuType)
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

            return '<div class="p-4">' . Livewire::mount($this->menuTypes[$menu['data_type']]['setting'], ['dataId' => $menu->id]) . '</div>';
        }
        return '<div class="p-4">Not found</div>';
    }
    public function renderCallback($callback)
    {
        $this->Callback = $callback;
        return $this;
    }
    public function renderItemCallback($callback)
    {
        $this->ItemCallback = $callback;
        return $this;
    }
    public function doRender($item, $_position = '')
    {
        if ($callback = $this->Callback) {
            if (is_callable($callback))
                $callback($item, $_position);
        }
        return $this;
    }
    public function doRenderItem($item, $_position = '')
    {
        if ($type = $item->getValueContentType()) {
            if (isset($this->menuTypes[$type])) {
                ($this->menuTypes[$type]['setting'])::RenderItem($item, $_position);

                return $this;
            }
        }

        if ($callback = $this->ItemCallback) {
            if (is_callable($callback))
                $callback($item, $_position);
        }
        return $this;
    }
}
