<?php

namespace Sokeio\Livewire;

use Sokeio\Components\FormMenu;
use Sokeio\Components\UI;

class MenuItemLink extends FormMenu
{
    protected function MenuUI()
    {
        return [
            UI::Text('url')->Label(__('URL'))->required(),
        ];
    }
}
