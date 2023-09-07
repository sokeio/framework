<?php

namespace BytePlatform\Livewire;

use BytePlatform\Component;
use BytePlatform\Forms\WithFormData;
use BytePlatform\ItemManager;
use Livewire\Attributes\Reactive;

class Form extends Component
{
    use WithFormData;
    #[Reactive]
    public ItemManager $manager;
    protected function ItemManager()
    {
        return $this->manager;
    }
}
