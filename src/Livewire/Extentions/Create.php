<?php

namespace Sokeio\Livewire\Extentions;

use Sokeio\Component;
use Illuminate\Support\Facades\Artisan;

class Create extends Component
{
    public $ExtentionType;
    public $InputName;
    public function doCreate()
    {
        if ($this->InputName != '') {
            \ob_start();
            Artisan::call('so:make-' . $this->ExtentionType, ['name' => [$this->InputName], '-f' => true, '-a' => true]);
            $output = \ob_get_clean();
            $this->showMessage($output);
            $this->refreshRefComponent();
            $this->closeComponent();
        }
    }
    public function render()
    {
        return view('sokeio::extentions.create');
    }
}
