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
    public function RegisterType($type, $title, $setting, $renderComponentOrCallback)
    {
        $this->menuTypes[$type] = [
            'type' => $type,
            'title' => $title,
            'setting' => $setting,
            'render' => $renderComponentOrCallback
        ];
    }
    public function getMenuType()
    {
        return collect($this->menuTypes);
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
                $render = $this->menuTypes[$type]['render'];
                if (is_callable($render)) {
                    $render($item, $_position);
                } else {
                    echo Livewire::mount($render, ['item' => $item, 'position' => $_position]);
                }
            }
        }

        if ($callback = $this->ItemCallback) {
            if (is_callable($callback))
                $callback($item, $_position);
        }
        return $this;
    }
}
