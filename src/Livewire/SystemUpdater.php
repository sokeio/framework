<?php

namespace Sokeio\Livewire;

use Illuminate\Support\Facades\Auth;
use Sokeio\Component;
use Sokeio\Marketplate;
use Sokeio\Platform;

class SystemUpdater extends Component
{
    public $isSystemVersionNew = false;
    public $productInfo = [];
    public $start = 3;
 
    public function begin()
    {
        while ($this->start >= 0) {
            // Stream the current count to the browser...
            $this->stream(  
                to: 'count',
                content: $this->start,
                replace: true,
            );
 
            // Pause for 1 second between numbers...
            sleep(1);
 
            // Decrement the counter...
            $this->start = $this->start - 1;
        };
    }
    public function checkSystemUpdate()
    {
        return Marketplate::checkNewVersion();
    }
    public function lazySystemUpdate()
    {
        if (Auth::check() && Platform::isUrlAdmin()) {
            $this->isSystemVersionNew = Marketplate::checkNewVersion();
            if ($this->isSystemVersionNew) {
                $this->productInfo = Marketplate::getNewVersionInfo();
            }
        }
    }
    public function render()
    {
        return view('sokeio::livewire.system-updater.index', [
            'message' => 'System Update is running'
        ]);
    }
}
