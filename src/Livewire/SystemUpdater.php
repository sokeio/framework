<?php

namespace Sokeio\Livewire;

use Sokeio\Component;

class SystemUpdater extends Component
{
    public function render()
    {
        return view('sokeio::livewire.system-updater.index',[
            'message' => 'System Updater is running'
        ]);
    }
}
