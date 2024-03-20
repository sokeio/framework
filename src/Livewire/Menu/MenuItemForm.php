<?php

namespace Sokeio\Livewire\Menu;

use Sokeio\Component;
use Sokeio\Facades\MenuRender;
use Sokeio\Models\Menu;

class MenuItemForm extends Component
{
    public $dataId;
    public Menu $item;
    public function mount()
    {
        $this->item = Menu::with('menuLocation')->find($this->dataId);
    }
    public function render()
    {
        return MenuRender::renderMenuSetting($this->item);
    }
}
