<?php

namespace Sokeio;

use Sokeio\Concerns\WithDoAction;
use Sokeio\Concerns\WithLivewire;
use Livewire\Component as ComponentBase;

class Component extends ComponentBase
{
    use WithDoAction, WithLivewire;
}
