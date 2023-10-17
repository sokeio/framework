<?php

namespace BytePlatform\Livewire;

use BytePlatform\Component;
use BytePlatform\Concerns\WithFormData;
use Livewire\Attributes\Reactive;

class Form extends Component
{
    use WithFormData;
    #[Reactive]
    public $manager;
    public $formCustom;
    protected function ItemManager()
    {
        if ($this->formCustom) {
            return app($this->manager)->getFormCustom($this->formCustom);
        }
        return app($this->manager)->FormPage();
    }
}
