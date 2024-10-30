<?php

namespace Sokeio\Livewire\DashboardSetting;

use Sokeio\Component;
use Sokeio\Models\Dashboard;
use Sokeio\Widget;

class Index extends Component
{
    public $dashboardId = 0;
    public $widgets = [];

    public function mount()
    {
        $this->widgets = Dashboard::find($this->dashboardId)?->widgets ?? [];
    }
    public function render()
    {
        return view('sokeio::livewire.dashboard-setting.index',[
            'widgetComponents' => Widget::getWidgets()
        ]);
    }
}
