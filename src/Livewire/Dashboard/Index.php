<?php

namespace Sokeio\Livewire\Dashboard;

use Sokeio\Component;
use Sokeio\Facades\Assets;
use Sokeio\Facades\Dashboard;

class Index extends Component
{
    public function mount()
    {
        Assets::setTitle(__('Dashboard'));
    }
    public function render()
    {
        return view('sokeio::dashboard.index', [
            'widgets' => Dashboard::getWidgetInDashboard('dashboard-default'),
            'positions' => Dashboard::getPosition()
        ]);
    }
}
