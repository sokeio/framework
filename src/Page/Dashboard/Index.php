<?php

namespace Sokeio\Page\Dashboard;

use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Sokeio\Models\Dashboard;
use Sokeio\Attribute\PageInfo;
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
    icon: 'ti ti-dashboard ',
    menu: true,
    sort: 0
)]
class Index extends \Sokeio\Page
{
    use WithUI;
    public array $dataSearch = [];
    public $dashboardKey = '';
    #[On('sokeio:refresh-dashboard')]
    public function refreshDashboard()
    {
        $this->soLoadData();
    }
    public function updatedDataSearch()
    {
        Session::put($this->dashboardKey, $this->dataSearch);
        $this->reUI();
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
        return PageUI::init([
            Div::init()->viewBlade('sokeio::pages.dashboard.index', [
                'widgets' => $this->getWidgets(),
                'dashboard_id' => data_get($this->dataSearch, 'dashboard_id')
            ])
        ])
            ->className('p-2')
            ->icon('ti ti-dashboard')->rightUI([
                Select::init('dashboard_id')->placeholder('Dashboard')
                    ->remoteActionWithModel(Dashboard::class)
                    ->valueDefault(data_get($this->dataSearch, 'dashboard_id'))
                    ->classNameWrapper('me-2')->debounce(10),
                // DatePicker::init('from_date')->placeholder(__('Start Date'))
                //     ->classNameWrapper('me-2')
                //     ->valueDefault(Carbon::now()->subDays(30))->debounce(10),
                // DatePicker::init('to_date')->placeholder(__('End Date'))
                //     ->valueDefault(Carbon::now())->debounce(10),
                Button::init()
                    ->text(__('Settings'))
                    ->icon('ti ti-settings')
                    ->className('btn btn-danger')
                    ->modalRoute($this->getRouteName('setting')),
            ])->setPrefix('dataSearch')
            ->render(function () {
                $this->updatedDataSearch();
            });
    }
}
