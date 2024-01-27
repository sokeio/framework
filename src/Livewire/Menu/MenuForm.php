<?php

namespace Sokeio\Livewire\Menu;

use Sokeio\Components\Form;
use Sokeio\Components\UI;
use Sokeio\Models\MenuLocation;

class MenuForm extends Form
{
    protected function getModel()
    {
        return MenuLocation::class;
    }
    protected function FormUI()
    {
        return UI::Prex('data', [
            UI::Text('name')->Label(__('Menu Name'))->required(),
        ])->ClassName('p-3');
    }
    protected function saveBefore($menu){
        $menu->locations=[];
    }
}
