<?php

namespace Sokeio\Livewire;

use Sokeio\Component;

class HomePage extends Component
{
    public function render()
    {
        return viewScope('sokeio::homepage');
    }
}
