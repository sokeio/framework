<?php

namespace Sokeio\Page\Dashboard;

use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Sokeio\Models\Dashboard;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Field\DatePicker;
use Sokeio\UI\Field\Select;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;
use Sokeio\Widget;

#[PageInfo(
    admin: true,
    auth: true,
    url: '/',
    route: 'dashboard',
    title: 'Dashboard',
    menuTitle: 'Dashboard',
    menu: true,
    sort: 0
)]
class Index extends \Sokeio\Page
{
    use WithUI;
    public array $dataSearch = [];
    public $dashboardKey = '';
    public function updatedDataSearch()
    {
        Session::put($this->dashboardKey, $this->dataSearch);
    }

    public function getWidgets(): array
    {
        $dashboardId = data_get($this->dataSearch, 'dashboard_id');
        $dashboard = null;
        if ($dashboardId) {
            $dashboard = Dashboard::find($dashboardId);
        }
        if (!$dashboard) {
            $dashboard = Dashboard::query()->where('is_default', true)->first();
        }
        if (!$dashboard) {
            return  Widget::getListWidgets();
        }
        if ($dashboard && (!$dashboardId || ($dashboard->id != $dashboardId))) {
            data_set($this->dataSearch, 'dashboard_id', $dashboard->id);
            $this->updatedDataSearch();
        }
        return $dashboard->widgets ?? [];
    }
    protected function setupUI()
    {
        if (!$this->dashboardKey) {
            $this->dashboardKey = 'dashboard-' . (time() . rand(1000, 9999));
        }
        return [
            PageUI::init([
                Div::init()->viewBlade('sokeio::pages.dashboard.index', [
                    'widgets' => $this->getWidgets(),
                    'dashboardKey' => $this->dashboardKey,
                    'dashboard_id' => data_get($this->dataSearch, 'dashboard_id')
                ])
            ])->title($this->getPageConfig()->getTitle())
                ->className('p-2')
                ->icon('ti ti-dashboard')->rightUI([
                    Select::init('dashboard_id')->placeholder('Dashboard')
                        ->remoteActionWithModel(Dashboard::class)
                        ->debounce(60)
                        ->valueDefault(data_get($this->dataSearch, 'dashboard_id'))
                        ->classNameWrapper('me-2'),
                    DatePicker::init('from_date')->placeholder(__('Start Date'))
                        ->classNameWrapper('me-2')
                        ->valueDefault(Carbon::now()->subDays(30)),
                    DatePicker::init('to_date')->placeholder(__('End Date'))
                        ->valueDefault(Carbon::now()),
                    Button::init()->text(__('Search'))->icon('ti ti-search')->wireClick(function () {
                        $this->alert(json_encode($this->dataSearch));
                    }),
                    Button::init()
                        ->text(__('Settings'))
                        ->icon('ti ti-settings')
                        ->className('btn btn-danger')
                        ->modalRoute($this->getRouteName('setting')),
                ])->setPrefix('dataSearch')
                ->render(function () {
                    $this->updatedDataSearch();
                })
        ];
    }
}
