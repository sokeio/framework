<?php

namespace Sokeio\Livewire\Extentions;

use Sokeio\Component;

class Store extends Component
{
    public $ExtentionType;
    public function render()
    {
        return view('sokeio::extentions.store');
    }
}
