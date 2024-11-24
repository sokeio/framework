<?php

namespace Sokeio\Livewire;

use Sokeio\Component;
use Sokeio\Theme;

class Socialite extends Component
{
    public function render()
    {
        return Theme::view('sokeio::livewire.socialite.index', [], [], true);
    }
}
