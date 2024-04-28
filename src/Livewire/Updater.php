<?php

namespace Sokeio\Livewire;

use Sokeio\Component;
use Sokeio\Components\Concerns\WithBreadcrumb;

class Updater extends Component
{
    use WithBreadcrumb;
    protected function getTitle()
    {
        return __('Sokeio Updater');
    }
    public function mount()
    {
        $this->doBreadcrumb();
    }
    public function doUpdate()
    {
        $this->showMessage('Update');
    }
    public function render()
    {
        return view('sokeio::updater');
    }
}
