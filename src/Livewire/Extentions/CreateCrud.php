<?php

namespace Sokeio\Livewire\Extentions;

use Sokeio\Component;

class CreateCrud extends Component
{
    public $ExtentionType;
    public $ExtentionId;
    public function doCreate()
    {
        // if ($this->InputName != '') {
        //     \ob_start();
        //     Artisan::call('mb:' . $this->ExtentionType, ['name' => [$this->InputName], '-f' => true, '-a' => true]);
        //     $output = \ob_get_clean();
        //     $this->showMessage($output);
        //     $this->refreshRefComponent();
        //     $this->closeComponent();
        // }
    }
    public function render()
    {
        return view('sokeio::extentions.create-crud');
    }
}
