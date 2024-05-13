<?php

namespace Sokeio\Livewire\Dashboard;

use Livewire\Attributes\Session;
use Sokeio\Component;

class FormWidgetSetting extends Component
{
    public $dashboardId;
    public $widgetId;
    #[Session('widget_settings')]
    public $widgets;
    public function render()
    {
        return "
        <div>" . json_encode($this->widgets) . "</div>
        ";
    }
}
