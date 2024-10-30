<?php

namespace Sokeio\Support\Widget;

use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Locked;
use Sokeio\Component;
use Sokeio\UI\Common\Div;
use Sokeio\UI\WithUI;
use Sokeio\Widget;

class WidgetComponent extends Component
{
    use WithUI;
    #[Locked]
    public $widgetId;
    #[Locked]
    public $dashboardKey;
    private WidgetSetting|null $widget = null;
    public function getWidget()
    {
        if (!$this->widget) {
            $this->widget =  Widget::getWidgetById($this->widgetId);
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
            Div::init([
                Div::init([
                    $this->getWidget()->getWidgetUI()
                ])->className('card')
            ])->className($this->getWidget()->getColumnClass())->className('p-3'),

        ];
    }
}
