<?php

namespace Sokeio\Livewire;

use Sokeio\Component;
use Sokeio\Form;

class Test extends Component
{
    public Form $data;
    public function render()
    {
        return view('sokeio::test');
    }
}
