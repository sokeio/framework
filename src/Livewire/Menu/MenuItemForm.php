<?php

namespace Sokeio\Livewire\Menu;

use Sokeio\Components\Form;
use Sokeio\Components\UI;
use Sokeio\Models\Menu;

class MenuItemForm extends Form
{
    protected function getModel()
    {
        return Menu::class;
    }
    public function doRemove()
    {
        $this->showMessage('Remove');
    }
    protected function FormUI()
    {
        return UI::Prex('data', [
            UI::Text('name')->Label(__('Name'))->required(),
        ])->ClassName('p-3');
    }
    protected function FooterUI()
    {
        return [
            UI::Div([
                UI::Button(__('Remove'))->Danger()->WireClick('doRemove()')->ClassName('m-2'),
                UI::Button(__('Save'))->WireClick('doSave()')->ClassName('m-2')
            ])->ClassName('p-2 text-center')
        ];
    }
    // protected function saveBefore($menu)
    // {
    //     if (!$menu->locations) {
    //         $menu->locations = [];
    //     }
    // }
    // protected function saveAfter($menu)
    // {
    //     $this->callFuncByRef('loadMenu', $menu->id);
    // }
}
