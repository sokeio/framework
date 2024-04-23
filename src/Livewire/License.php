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
        $rs =  Client::checkLicenseKey($this->licenseKey);
        if (isset($rs['status']) && $rs['status'] == 1) {
            $this->showMessage($rs['message']);
        } else {
            $this->showMessage(__('Invalid license key'));
        }
    }
    public function render()
    {
        return view('sokeio::license', [
            'domain' => request()->getHost(),
            'prodcutId' => Client::getProductId(),
            'licenseInfo' => Client::getLicense(),
        ]);
    }
}
