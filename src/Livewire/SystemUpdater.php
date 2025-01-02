<?php

namespace Sokeio\Livewire;

use Sokeio\Component;
use Sokeio\Marketplate;

class SystemUpdater extends Component
{
    public $isSystemVersionNew = false;
    public function checkSystemUpdate()
    {
        return Marketplate::checkNewVersion();
    }
    public function lazySystemUpdate()
    {
        $this->isSystemVersionNew = Marketplate::checkNewVersion();
    }
    public function render()
    {
        return view('sokeio::livewire.system-updater.index', [
            'message' => 'System Update is running'
        ]);
    }
}
