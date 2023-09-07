<?php

namespace BytePlatform\Livewire;

use BytePlatform\Component;
use BytePlatform\Tables\WithTablePageData;

class TablePage extends Component
{
    use WithTablePageData;
    public $manager;
    public $formCustom;
    protected function ItemManager()
    {
        if ($this->formCustom) {
            return app($this->manager)->getFormCustom($this->formCustom);
        }
        return app($this->manager)->TablePage();
    }
}
