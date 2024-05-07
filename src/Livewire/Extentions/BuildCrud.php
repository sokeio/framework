<?php

namespace Sokeio\Livewire\Extentions;

use PDO;
use Sokeio\Component;
use Sokeio\Facades\Platform;

class BuildCrud extends Component
{
    public $models = [];
    public $modelCurent = null;
    public function mount()
    {
        $this->models = Platform::getModels();
    }
    public function render()
    {
        return view('sokeio::extentions.build-crud');
    }
}
