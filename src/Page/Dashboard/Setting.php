<?php

namespace Sokeio\Page\Dashboard;

use Sokeio\Models\Dashboard;
use Sokeio\Attribute\PageInfo;
use Sokeio\UI\Common\Div;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;

#[PageInfo(
    admin: true,
    auth: true,
    title: 'Dashboard Setting',
    route: 'dashboard.setting',
)]
class Setting extends \Sokeio\Page
{
    use WithUI;
    public $dashboardId = 0;
    public function selectDashboard($id)
    {
        $this->dashboardId = $id;
        $this->refreshMe();
    }
    public function addDashboard($name)
    {
        Dashboard::create(['name' => $name]);
        $this->refreshMe();
        $this->refreshDashboard();
    }
    public function deleteDashboard($id)
    {
        $dashboard = Dashboard::query()->find($id);
        $dashboard->delete();
        $this->refreshMe();
        $this->refreshDashboard();
    }

    protected function setupUI()
    {
        if (!Dashboard::query()->where('id', $this->dashboardId)->exists()) {
            $this->dashboardId = 0;
        }
        $dashboards = Dashboard::query()->orderBy('id', 'desc')->get(['id', 'name']);
        if ($this->dashboardId <= 0 && $dashboards->count() > 0) {
            $this->dashboardId = $dashboards->first()?->id ?? 0;
        }
        return PageUI::init([
            Div::init()->className('row g-0')->viewBlade('sokeio::pages.dashboard.setting', [
                'dashboards' => $dashboards,
                'dashboardId' => $this->dashboardId,
            ])
        ])
            ->icon('ti ti-settings')
            ->className('p-2')
            ->xlSize()

            ->afterUI([
                // Div::init([
                //     Button::init()->text(__('Cancel'))->className('btn btn-warning me-2')
                //         ->modalClose()->icon('ti ti-x'),
                //     Button::init()->text(__('Create'))->wireClick('saveData')->icon('ti ti-device-floppy')
                // ])
                //     ->className('px-2 pt-2 d-flex justify-content-end')
            ]);
    }
}
