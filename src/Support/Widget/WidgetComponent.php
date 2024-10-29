<?php

namespace Sokeio\Support\Widget;

use Sokeio\Component;
use Sokeio\UI\Common\Div;
use Sokeio\UI\WithUI;

class WidgetComponent extends Component
{
    use WithUI;
    public $widgetId;
    public $dashboardKey;
    protected function setupUI()
    {
        return [
            Div::init([
                $this->dashboardKey
            ])
        ];
    }
}
