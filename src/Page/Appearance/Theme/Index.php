<?php

namespace Sokeio\Page\Appearance\Theme;

use Sokeio\Support\Livewire\PageInfo;
use Sokeio\Theme;


#[PageInfo(
    admin: true,
    auth: true,
    title: 'Theme',
    menu: true,
    menuTitle: 'Theme',
    sort: 0
)]
class Index extends \Sokeio\Page
{
    public function render()
    {
        return Theme::view('sokeio::pages.appearance.theme.index');
    }
}
