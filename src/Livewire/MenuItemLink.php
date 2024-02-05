<?php

namespace Sokeio\Livewire;

use Sokeio\Components\FormMenu;
use Sokeio\Components\UI;
use Sokeio\Menu\MenuItemBuilder;

class MenuItemLink extends FormMenu
{

    public static function RenderItem(MenuItemBuilder $item)
    {
        echo  view_scope('sokeio::menu.item.link', ['item' => $item])->render();
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
            UI::Text('link')->Label(__('URL'))->required()->ValueDefault(function () {
                return '#';
            }),
        ];
    }
}
