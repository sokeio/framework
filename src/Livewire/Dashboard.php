<?php

namespace Sokeio\Livewire;

use Sokeio\Component;
use Sokeio\Facades\Assets;

class Dashboard extends Component
{
    public function mount()
    {
        Assets::setTitle(__('Dashboard'));
    }
    public function render()
    {
        return view('sokeio::dashboard.index', [
            'page_title' => __('Dashboard')
        ]);
    }
}
