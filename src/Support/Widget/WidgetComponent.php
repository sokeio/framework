<?php

namespace Sokeio\Support\Widget;

use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Locked;
use Sokeio\Component;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Field\SwitchField;
use Sokeio\UI\WithUI;

class WidgetComponent extends Component
{
    use WithUI;
    #[Locked]
    public $widgetData;
    #[Locked]
    public $dashboardKey;
    #[Locked]
    public $dashboardId;

    private WidgetSetting|null $widget = null;
    public function getWidget()
    {
        if (!$this->widget) {
            $this->widget = WidgetSetting::parse($this->widgetData);
        }
        return $this->widget;
    }

    public function getDataSearch()
    {
        return Session::get($this->dashboardKey, []);
    }

    protected function setupUI()
    {
        return [
            Div::make([
                Div::make([
                    $this->getWidget()?->getWidgetUI()
                ])->className('card')
            ])->className($this->getWidget()->getColumnClass()),

        ];
    }
}
