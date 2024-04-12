<?php

namespace Sokeio\Livewire;

use Sokeio\Facades\Dashboard;
use Sokeio\Component;

class DashboardSetting extends Component
{
    public function changeWidget($id)
    {
        $widget = Dashboard::getWidgetByKey($id);
        if ($widget) {
            if ($widget->isActive())
                $widget->block();
            else $widget->active();
        }
        $this->refreshRefComponent();
    }
    public function render()
    {
        return view('sokeio::settings.dashboard', [
            'page_title' => __('Dashboard Setting'),
            'widgets' => Dashboard::getWidget()
        ]);
    }
}
