<?php

namespace Sokeio\Livewire\Extentions;

use Sokeio\Component;

class Upload extends Component
{
    public $ExtentionType;
    public function render()
    {
        return view('sokeio::extentions.upload');
    }
}
