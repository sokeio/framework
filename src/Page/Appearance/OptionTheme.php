<?php

namespace Sokeio\Page\Appearance;

use Sokeio\Support\Livewire\PageInfo;
use Sokeio\Theme;

#[PageInfo(
    admin: true,
    auth: true,
    title: 'Option Theme',
    menu: true,
    menuTitle: 'Option Theme',
)]
class OptionTheme extends \Sokeio\Page
{
    public function render()
    {
        return Theme::view('sokeio::pages.appearance.option');
    }
}
