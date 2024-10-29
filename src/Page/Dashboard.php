<?php

namespace Sokeio\Page;

use Carbon\Carbon;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\DatePicker;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;

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
    protected function setupUI()
    {
        return [
            PageUI::init([])->title($this->getPageConfig()->getTitle())
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
