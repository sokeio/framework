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
    public $dashboardName = "";
    public $dashboardDescription = "";
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
            $this->dashboardName = $dashboard->name;
            $this->dashboardDescription = $dashboard->description;
        }
        $this->widgets = $dashboard?->widgets ?? [];
    }
    public function save()
    {
        $this->paramFillToWidget();
        $dashboard = Dashboard::find($this->dashboardId);
        $dashboard->name = $this->dashboardName;
        $dashboard->description = $this->dashboardDescription;
        $dashboard->widgets = $this->widgets;
        $dashboard->save();
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
