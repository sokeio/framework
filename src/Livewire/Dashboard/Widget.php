<?php

namespace Sokeio\Livewire\Dashboard;

use Livewire\Attributes\Reactive;
use Sokeio\Component;
use Sokeio\Components\Concerns\WithActionUI;
use Sokeio\Facades\Dashboard;

class Widget extends Component
{
    use WithActionUI;
    #[Reactive]
    public $widgetId;
    #[Reactive]
    public $dashboardId;

    public function render()
    {
        return view('sokeio::dashboard.widget', [
            'widget' => Dashboard::getWidgetComponent($this->dashboardId, $this->widgetId, $this),
        ]);
    }
}
