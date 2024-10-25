<?php

namespace Sokeio\Livewire\Theme;

use Sokeio\Component;

class Manager extends Component {

    public function render()
    {
        return <<<'blade'
            <div>
                <h3>
                $QUOTE$
                </h3>
            </div>
        blade;
    }
}
