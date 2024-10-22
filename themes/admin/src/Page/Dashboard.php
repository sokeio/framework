<?php

namespace SokeioTheme\Admin\Page;

use Sokeio\Support\Livewire\PageInfo;
use Sokeio\Theme;

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
    public function render()
    {
        return Theme::view('pages.dashboard');
    }
}
