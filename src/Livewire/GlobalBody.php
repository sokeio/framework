<?php

namespace Sokeio\Livewire;

use Sokeio\Component;

class GlobalBody extends Component
{
    public $timer = 0;
    public function render()
    {

        return view('sokeio::livewire.global-body');
    }
    public function test()
    {
        $this->timer += 1;
    }
}
