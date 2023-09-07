<?php

namespace BytePlatform\Livewire;

use Illuminate\Support\Str;
use BytePlatform\Component;
use BytePlatform\Dashboard;
use BytePlatform\DataForm;
use BytePlatform\Livewire\Dashboard as LivewireDashboard;

class SettingWidget extends Component
{
    public $widgets = [];
    public $widgetId;
    public DataForm $form;
    public function mount()
    {
        foreach (Dashboard::GetWidgets() as $key => $item) {
            $this->widgets[$key] = $item->getName();
        }
        if ($this->widgetId) {
            foreach (Dashboard::getWidgetSettingByKey($this->widgetId) as $key => $value) {
                $this->form->{$key} = $value;
            }
        }
    }
    private function closeAndRefreshData($message)
    {
        $this->closeComponent();
        $this->showMessage($message);
    }
    public function RemoveWidget()
    {
        if ($this->widgetId) {
            Dashboard::UnActiveWidget($this->widgetId);
        }
        $this->closeAndRefreshData('Remove widget success!');
        $this->dispatch('DashboardRefreshData');
    }
    public function UpdateWidget()
    {
        $this->widgetId = $this->widgetId ?? Str::random(40);
        $this->form->widgetId = $this->widgetId;
        Dashboard::ActiveWidget($this->widgetId, $this->form->toArray());
        $this->closeAndRefreshData('Update widget success!');
        $this->refreshRefComponent();
    }
    public function render()
    {
        return view('byte::setting-widget', [
            'WidgetItem' => Dashboard::getWidgetByKey($this->form->widgetType)
        ]);
    }
}
