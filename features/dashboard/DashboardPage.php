<?php

namespace Sokeio\Dashboard;

use Sokeio\UI\Common\Div;
use Sokeio\UI\Common\LivewireUI;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;

class DashboardPage extends \Sokeio\Page
{
    use WithUI;
    public $dashboardPageKey = 'dashboard';
    public function mount()
    {
        $this->dashboardPageKey = 'dashboard-' . time();
    }
    protected function getDashboardKey()
    {
        return 'default';
    }
    protected function getGroupWidget()
    {
        return [
            'default'
        ];
    }
    private function getWidgets()
    {
        return Dashboard::getDashboard($this->getDashboardKey());
    }

    protected function setupUI()
    {
        $widgets = $this->getWidgets();
        return PageUI::make(collect($this->getGroupWidget())->map(function ($group) use ($widgets) {
            return Div::make($widgets->where(function ($item) use ($group) {
                return $item['info']->position == $group;
            })->map(function ($item) {
                return LivewireUI::make()->component('sokeio::widget-component')->attr('widgetId', $item['key'])->attr('dashboardKey', $this->dashboardPageKey);
            }));
        }));
    }
}
