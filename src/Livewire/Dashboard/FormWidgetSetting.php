<?php

namespace Sokeio\Livewire\Dashboard;

use Livewire\Attributes\Session;
use Sokeio\Components\Form;

class FormWidgetSetting extends Form
{
    public $dashboardId;
    public $widgetId;
    #[Session('widget_settings')]
    public $widgets;
}
