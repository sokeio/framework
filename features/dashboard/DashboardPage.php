<?php

namespace Sokeio\Dashboard;

use Sokeio\Livewire\Form;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Common\LivewireUI;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;

class DashboardPage extends \Sokeio\Page
{
    use WithUI;
    public $dashboardPageKey = 'dashboard';
    public Form $dataSearch;
    public function updatedDataSearch()
    {
        Dashboard::setDataDashboard($this->dashboardPageKey, $this->dataSearch->toArray());
        $this->updateWidgetInDashboard();
    }
    public function mount()
    {
        $this->dashboardPageKey = 'dashboard-' . time();
    }
    protected function updateWidgetInDashboard()
    {
        $this->dispatch('updateWidgetData' . $this->dashboardPageKey);
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
    private function checkShow($item)
    {
        return isset($item['info']) && $item['info']->show;
    }
    protected function setupUI()
    {
        $widgets = $this->getWidgets();
        return PageUI::make(
            collect($this->getGroupWidget())
                ->map(function ($group) use ($widgets) {
                    return Div::make(
                        $widgets->where(function ($item) use ($group) {
                            return $item['info']->position == $group && $this->checkShow($item);
                        })->map(function ($item) {
                            return LivewireUI::make()
                                ->component('sokeio::widget-component')
                                ->attr('widgetId', $item['key'])
                                ->attr('dashboardKey', $this->dashboardPageKey);
                        })
                    )->className(' row g-1 so-dashboard-group-' . $group);
                })
        )->render(function () {
            $this->updateWidgetInDashboard();
        })->className('so-dashboard')
            ->rightUI([
                Button::make()->label(__('Settings'))
                ->icon('ti ti-dashboard')
            ]);
    }
}
