<?php

namespace Sokeio\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Sokeio\Component;
use Sokeio\Marketplate;
use Sokeio\Platform;

class SystemUpdater extends Component
{
    use WithFileUploads;
    public $isSystemVersionNew = false;
    public $productInfo = [];
    public $start = 100000;
    private function checkUpdatePermission()
    {
        return Auth::check() && Platform::isUrlAdmin() && Platform::gate()->isSupperAdmin();
    }
    public function startUpdate()
    {
        $secret = rand(100000000, 999999999);
        if ($this->checkUpdatePermission()) {
            // Run command so:system-update in background
            Platform::artisanInBackground('so:system-update', $secret);
        }
        return url($secret);
    }
    public function checkUpdateStatus()
    {
        return Marketplate::statusUpdate();
    }
    public function checkSystemUpdate()
    {
        return Marketplate::checkNewVersion();
    }
    public function lazySystemUpdate()
    {
        if (!$this->checkUpdatePermission()) {
            return;
        }
        $this->isSystemVersionNew = Marketplate::checkNewVersion();
        if ($this->isSystemVersionNew) {
            $this->productInfo = Marketplate::getNewVersionInfo();
        }
    }
    public function render()
    {
        return view('sokeio::livewire.system-updater.index', [
            'message' => 'System Update is running'
        ]);
    }
}
