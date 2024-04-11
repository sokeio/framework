<?php

namespace Sokeio\Livewire;

use Sokeio\Component;
use Sokeio\Components\Concerns\WithBreadcrumb;

class License extends Component
{
    public $licenseKey = '';
    use WithBreadcrumb;
    protected function getTitle()
    {
        return __('License');
    }
    public function mount()
    {
        $this->doBreadcrumb();
    }
    public function doLicense()
    {
        $this->showMessage('License key ' . $this->licenseKey . ' is not valid');
    }
    public function render()
    {
        return view('sokeio::license', [
            'domain' => request()->getHost(),
        ]);
    }
}
