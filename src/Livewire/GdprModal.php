<?php

namespace Sokeio\Livewire;

use Illuminate\Support\Facades\Cookie;
use Sokeio\Component;

class GdprModal extends Component
{
    public function allowAll()
    {
        $this->closeComponent();
        Cookie::queue('cookie-consent', 'true', 60 * 24 * 365, null, null, false, false);
    }
    public function render()
    {
        return view('sokeio::gdpr-modal');
    }
}
