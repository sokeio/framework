<?php

namespace Sokeio\Support\Livewire;

use Livewire\Component as ComponentBase;
use Sokeio\Support\Livewire\Concerns\WithLivewire;
use Sokeio\Theme;

class Component extends ComponentBase
{
    use  WithLivewire;
    public function placeholder(array $params = [])
    {
        return Theme::view('sokeio::partials.placeholder', $params, [], true);
    }
}
