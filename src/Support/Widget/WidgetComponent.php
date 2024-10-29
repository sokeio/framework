<?php

namespace Sokeio\Support\Widget;

use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Locked;
use Sokeio\Component;
use Sokeio\UI\Common\Div;
use Sokeio\UI\WithUI;

class WidgetComponent extends Component
{
    use WithUI;
    #[Locked]
    public $widgetId;
    #[Locked]
    public $dashboardKey;
    public function getDataSearch()
    {
        return Session::get($this->dashboardKey, []);
    }
    protected function setupUI()
    {
        return [
            Div::init([
                $this->dashboardKey,
                json_encode($this->getDataSearch())
            ])
        ];
    }
}
