<?php

namespace Sokeio\Livewire;

use Sokeio\Components\FormMenu;
use Sokeio\Components\UI;

class MenuItemLink extends FormMenu
{
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
