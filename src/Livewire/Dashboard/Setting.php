<?php

namespace Sokeio\Livewire\Dashboard;

use Livewire\Attributes\Session;
use Sokeio\Component;
use Sokeio\Facades\Dashboard as FacadesDashboard;

class Setting extends Component
{
    #[Session('widget_settings')]
    public $widgets;
    public $positions;
    public function mount()
    {
        $this->widgets = FacadesDashboard::getWidgetInDashboard('dashboard-default');
        $this->positions = FacadesDashboard::getPosition();
    }
    public function changeWidget($data = [])
    {
        $this->showMessage(json_encode($data));
        $this->widgets = collect($this->widgets)->map(function ($item) use ($data) {
            if ($item['id'] == $data['id']) {
                $item = $data;
            }
            return $item;
        })->toArray();
    }
    public function render()
    {
        return view('sokeio::dashboard.setting');
    }
}
