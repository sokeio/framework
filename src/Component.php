<?php

namespace BytePlatform;

use BytePlatform\Concerns\WithDoAction;
use BytePlatform\Concerns\WithLivewire;
use Livewire\Component as ComponentBase;

class Component extends ComponentBase
{
    use WithDoAction, WithLivewire;
}
