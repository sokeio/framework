<?php

namespace Sokeio\Page;

use Sokeio\Support\Livewire\PageInfo;
use Sokeio\Theme;
use Sokeio\UI\PageUI;
use Sokeio\UI\Tab\TabControl;
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
    protected function setupUI()
    {
        return [
            PageUI::init([])->title($this->getPageConfig()->getTitle())
                ->className('p-2')
            ->icon('ti ti-dashboard')
        ];
    }
}
