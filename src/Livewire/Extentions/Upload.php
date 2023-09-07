<?php

namespace BytePlatform\Livewire\Extentions;

use BytePlatform\Component;

class Upload extends Component
{
    public $ExtentionType;
    public function render()
    {
        return view('byte::extentions.upload');
    }
}
