<?php

namespace Sokeio\Livewire\Menu;

use Sokeio\Components\Form;
use Sokeio\Components\UI;
use Sokeio\Models\MenuLocation;

class MenuForm extends Form
{
    protected function getModel(): string
    {
        return MenuLocation::class;
    }
    protected function formUI()
    {
        return UI::prex('data', [
            UI::text('name')->label(__('Menu Name'))->required(),
        ])->className('p-3');
    }
    protected function saveBefore($menu)
    {
        if (!$menu->locations) {
            $menu->locations = [];
        }
    }
    protected function saveAfter($menu)
    {
        $this->callFuncByRef('loadMenu', $menu->id);
    }
}
