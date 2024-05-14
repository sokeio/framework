<?php

namespace Sokeio\Livewire\Dashboard;

use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Session;
use Sokeio\Component;
use Sokeio\Facades\Dashboard;

class Setting extends Component
{
    #[Session('widget_settings')]
    public $widgets;
    public $positions;
    public $dashboardId = 'dashboard-default';
    public function mount()
    {
        $this->widgets = Dashboard::getWidgetInDashboard($this->dashboardId);
        $this->positions = Dashboard::getPosition();
    }
    public function updatePosition($position, $items)
    {
        $temp1 = collect($this->widgets)->where('position', '!=', $position)->toArray();
        $temp2 = collect($items)->map(function ($item) use ($position) {
            return collect($this->widgets)->where('id', $item['value'])
                ->where('position', '=', $position)->first() ?? [];
        })->toArray();
        $this->widgets = [
            ...$temp1,
            ...$temp2
        ];
        Log::info(['updatePosition', $this->widgets]);
    }
    public function changeWidget($data = [])
    {
        if (isset($data['id'])) {
            $this->widgets = collect($this->widgets)->map(function ($item) use ($data) {
                if ($item['id'] == $data['id']) {
                    $item = $data;
                }
                return $item;
            })->toArray();
        } else {
            $this->widgets = [
                ...$this->widgets,
                [
                    'id' => 'widget-' . uniqid(),
                    ...$data,

                ]
            ];
        }
    }
    public function saveSettings()
    {
        Dashboard::store($this->dashboardId, $this->widgets);
        $this->closeComponent();
        $this->refreshRefComponent();
        $this->showMessage(__('Saved successfully'));
    }
    public function render()
    {
        return view('sokeio::dashboard.setting');
    }
}
