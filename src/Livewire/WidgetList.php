<?php

namespace BytePlatform\Livewire;

use BytePlatform\Component;
use Livewire\Attributes\Reactive;
use BytePlatform\Dashboard as BytePlatformDashboard;

class WidgetList extends Component
{
    #[Reactive]
    public $widgets;
    #[Reactive]
    public $locked = false;
    public function updateWidgetOrder($data)
    {
        $data =  collect($data)->map(function ($item) {
            return $item['value'];
        })->toArray();
        BytePlatformDashboard::Update($data);
        $this->skipRender();
        $this->dispatch('DashboardRefreshData');
    }
    public function render()
    {
        return view('byte::widget-list');
    }
}
