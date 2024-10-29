<?php

namespace Sokeio\Page;

use Carbon\Carbon;

use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Field\DatePicker;
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
class Dashboard extends \Sokeio\Page
{
    use WithUI;
    public array $dataSearch = [];
    public $dashboardKey = '';

    public function getWidgets(): array
    {
        return Widget::getListWidgets();
    }
    protected function setupUI()
    {
        if (!$this->dashboardKey) {
            $this->dashboardKey = 'dashboard-' . (time() . rand(1000, 9999));
        }
        return [
            PageUI::init([
                Div::init()->viewBlade('sokeio::pages.dashboard', [
                    'widgets' => $this->getWidgets(),
                    'dashboardKey' => $this->dashboardKey
                ])
            ])->title($this->getPageConfig()->getTitle())
                ->className('p-2')
                ->icon('ti ti-dashboard')->rightUI([
                    DatePicker::init('from_date')->placeholder(__('Start Date'))
                        ->classNameWrapper('me-2')
                        ->valueDefault(Carbon::now()->subDays(30)),
                    DatePicker::init('to_date')->placeholder(__('End Date'))
                        ->valueDefault(Carbon::now()),
                    Button::init()->text(__('Search'))->icon('ti ti-search')
                ])->setPrefix('dataSearch')
        ];
    }
}
