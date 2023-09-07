<?php

namespace BytePlatform\Livewire\Extentions;

use BytePlatform\Component;

class Store extends Component
{
    public $ExtentionType;
    public function render()
    {
        return view('byte::extentions.store');
    }
}
