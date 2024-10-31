<?php

namespace Sokeio\Livewire\DashboardSetting;

use Sokeio\Component;
use Sokeio\Models\Dashboard;
use Sokeio\UI\Common\Div;
use Sokeio\UI\WithUI;
use Sokeio\Widget;

class Index extends Component
{
    use WithUI;
    public $dashboardId = 0;
    public $widgets = [];
    public $widgetParams = [];
    public $widgetId = "";
    public $name = "";
    public $description = "";
    public $isDefault = false;
    public $isActive = false;
    public $isPrivate = false;

    private function paramFillToWidget()
    {
        if ($this->widgetId) {
            foreach ($this->widgets as $key => $item) {
                if ($item['id'] === $this->widgetId) {
                    $item['params'] = $this->widgetParams;
                    $this->widgets[$key] = $item;
                    break;
                }
            }
        }
    }
    public function chooseWidget($widgetId)
    {
        $this->paramFillToWidget();
        $this->widgetId = $widgetId;
        foreach ($this->widgets as  $item) {
            if ($item['id'] === $this->widgetId) {
                $this->widgetParams = $item['params'];
                break;
            }
        }
        $this->reUI();
    }
    protected function setupUI()
    {
        foreach ($this->widgets as $widget) {
            if ($widget['id'] !== $this->widgetId) {
                continue;
            }
            $key = $widget['key'];
            $class = data_get(Widget::getWidget($key), 'class');
            if ($class && class_exists($class)) {
                return Div::init(($class)::paramUI())->setPrefix('widgetParams');
            }
        }
        return [];
    }
    public function addWidget($key, $group, $column = 'column3', $params = [])
    {
        if ($wire = Widget::getWidget($key)) {
            $id = uniqid('widget-');
            $this->widgets[] = [
                'id' => $id,
                'key' => $key,
                'group' => $group,
                'column' => $column,
                'params' => $params,
                'name' => $wire['name'],
            ];
        }
    }

    public function mount()
    {
        $dashboard = Dashboard::find($this->dashboardId);
        if ($dashboard) {
            $this->name = $dashboard->name;
            $this->description = $dashboard->description;
            $this->isDefault = $dashboard->is_default;
            $this->isActive = $dashboard->is_active;
            $this->isPrivate = $dashboard->is_private;
        }
        $this->widgets = $dashboard?->widgets ?? [];
    }
    public function save()
    {
        $this->paramFillToWidget();
        if ($this->isDefault) {
            Dashboard::query()->update(['is_default' => false]);
        }
        $dashboard = Dashboard::find($this->dashboardId);
        $dashboard->name = $this->name;
        $dashboard->description = $this->description;
        $dashboard->is_default = $this->isDefault;
        $dashboard->is_active = $this->isActive;
        $dashboard->is_private = $this->isPrivate;
        $dashboard->widgets = $this->widgets;
        $dashboard->save();
        $this->refreshParentMe();
    }
    public function remove()
    {
        $dashboard = Dashboard::find($this->dashboardId);
        $dashboard->delete();
        $this->refreshParentMe();
    }
    public function render()
    {
        return view('sokeio::livewire.dashboard-setting.index', [
            'widgetComponents' => Widget::getWidgets(),
            'widgetSettings' => $this->getUI()->render()
        ]);
    }
}
