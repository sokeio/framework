<?php

namespace SokeioTheme\Admin\Page;

use Sokeio\Support\Livewire\PageInfo;
use Sokeio\Theme;

#[PageInfo(admin: true, auth: true, url: '/', route: 'dashboard', title: 'Dashboard')]
class Dashboard extends \Sokeio\Page
{
    public function render()
    {
        return Theme::view('pages.dashboard');
    }
}
