<?php

namespace Sokeio\Livewire\DashboardSetting;

use Sokeio\Component;
use Sokeio\Models\Dashboard;
use Sokeio\UI\SoUI;
use Sokeio\UI\WithUI;
use Sokeio\Widget;

class Index extends Component
{
    use WithUI;
    public $dashboardId = 0;
    public $widgets = [];
    public $widgetId = "";
    public $dashboardName = "";
    public $dashboardDescription = "";
    public function chooseWidget($widgetId)
    {
        $this->widgetId = $widgetId;
        // $this->skipRender();
        // $widget = Widget::getWidget($widgetKey);
        // $class = data_get($widget, 'class');
        // if ($widget && $class && class_exists($class)) {
        //     return SoUI::init(($class)::paramUI(), $this)->toHtml();
        // }
        // return "NOK";
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
                return SoUI::init(($class)::paramUI(), $this)->toHtml();
            }
        }
        return [];
    }
    public function addWidget($key, $group, $column = 'column3', $params = [])
    {
        // [
        //     'key' => 'sokeio:count-model',
        //     'id' => 'widget-1',
        //     'group' => 'top',
        //     'column' => 'column3',
        //     'params' => [
        //         'model' => null,
        //         'title' => 'Data 1'
        //     ]
        // ],
        if ($wire = Widget::getWidget($key)) {
            $this->widgets[] = [
                'id' => uniqid('widget-'),
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
        $this->widgets = Dashboard::find($this->dashboardId)?->widgets ?? [];
    }
    public function render()
    {
        return view('sokeio::livewire.dashboard-setting.index', [
            'widgetComponents' => Widget::getWidgets(),
            'widgetSettings' => $this->getUI()->render()
        ]);
    }
}
