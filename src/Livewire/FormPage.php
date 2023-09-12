<?php

namespace BytePlatform\Livewire;

use BytePlatform\Component;
use BytePlatform\Forms\WithFormData;

class FormPage extends Component
{
    use WithFormData;
    public $manager;
    public $formCustom;
    protected function ItemManager()
    {
        if ($this->formCustom) {
            return app($this->manager)->getFormCustom($this->formCustom);
        }
        return app($this->manager)->FormPage();
    }
    protected function getView()
    {
        return 'byte::forms.page';
    }
}
