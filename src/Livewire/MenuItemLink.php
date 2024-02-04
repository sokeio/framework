<?php

namespace Sokeio\Livewire;

use Sokeio\Components\FormMenu;
use Sokeio\Components\UI;
use Sokeio\Menu\MenuItemBuilder;

class MenuItemLink extends FormMenu
{

    public static function RenderItem(MenuItemBuilder $item)
    {
    }
    public static function getMenuName()
    {
        return __('Link');
    }
    public static function getMenuType()
    {
        return 'MenuItemLink';
    }
    protected function MenuUI()
    {
        return [
            UI::Text('url')->Label(__('URL'))->required(),
        ];
    }
}
