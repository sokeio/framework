<?php

namespace Sokeio\Support\Livewire;

use Attribute;
use Sokeio\Component;
use Sokeio\UI\WithUI;

#[Attribute(Attribute::TARGET_CLASS)]
class WidgetComponent extends Component
{
    use WithUI;
    protected function setupUI()
    {
        return [];
    }
}
