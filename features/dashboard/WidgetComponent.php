<?php

namespace Sokeio\Dashboard;

use Livewire\Attributes\Locked;
use Sokeio\Component;
use Sokeio\UI\Common\Div;
use Sokeio\UI\WithUI;

class WidgetComponent extends Component
{
    use WithUI;
    protected function getListeners()
    {
        return [
            'refreshData' => 'soLoadData',
            'refreshData' . $this->getId() => 'soLoadData',
            'updateWidgetData' . $this->dashboardKey => 'soLoadData',
        ];
    }
    #[Locked]
    public $dashboardKey;
    #[Locked]
    public $widgetId;



    protected function setupUI()
    {
        return [
            Div::make([
                $this->widgetId,
                '-',
                $this->dashboardKey,
                '-',
                'This is a widget component',
            ]),

        ];
    }
}
