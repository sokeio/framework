<?php

namespace Sokeio\Livewire;

use Illuminate\Support\Facades\Auth;
use Sokeio\Component;
use Sokeio\Marketplate;
use Sokeio\Platform;

class SystemUpdater extends Component
{
    public $isSystemVersionNew = false;
    public function checkSystemUpdate()
    {
        return Marketplate::checkNewVersion();
    }
    public function lazySystemUpdate()
    {
        if (Auth::check() && Platform::isUrlAdmin()) {
            $this->isSystemVersionNew = Marketplate::checkNewVersion();
        }
    }
    public function getProductInfo()
    {
        return Marketplate::cacheProductInfo();
    }
    public function render()
    {
        return view('sokeio::livewire.system-updater.index', [
            'message' => 'System Update is running'
        ]);
    }
}
