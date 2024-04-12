<?php

namespace Sokeio\Livewire;

use Sokeio\Component;
use Sokeio\Components\Concerns\WithBreadcrumb;
use Sokeio\Facades\Client;

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
        if (!Client::checkLicenseKey($this->licenseKey)) {
            $this->showMessage('License key ' . $this->licenseKey . ' is valid');
        }
    }
    public function render()
    {
        return view('sokeio::license', [
            'domain' => request()->getHost(),
        ]);
    }
}
