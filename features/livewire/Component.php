<?php

namespace Sokeio\Livewire;

use Livewire\Component as ComponentBase;
use Sokeio\Livewire\Concerns\WithLivewire;
use Sokeio\Theme;

class Component extends ComponentBase
{
    use  WithLivewire;
    public function placeholder(array $params = [])
    {
        return Theme::view('sokeio::partials.placeholder', $params, [], true);
    }
}
