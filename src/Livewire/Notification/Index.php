<?php

namespace Sokeio\Livewire\Notification;

use Sokeio\Component;
use Sokeio\Theme;

class Index extends Component
{
    public function render()
    {
        return Theme::view('sokeio::livewire.notification.index', [], [], true);
    }
}
