<?php

namespace BytePlatform\Livewire\Extentions;

use BytePlatform\Component;
use Illuminate\Support\Facades\Artisan;

class Create extends Component
{
    public $ExtentionType;
    public $InputName;
    public function doCreate()
    {
        if ($this->InputName != '') {
            \ob_start();
            Artisan::call('mb:' . $this->ExtentionType, ['name' => [$this->InputName], '-f' => true, '-a' => true]);
            $output = \ob_get_clean();
            $this->showMessage($output);
            $this->refreshRefComponent();
            $this->closeComponent();
        }
    }
    public function render()
    {
        return view('byte::extentions.create');
    }
}
