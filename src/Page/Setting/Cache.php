<?php

namespace Sokeio\Page\Setting;

use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;

#[PageInfo(
    admin: true,
    auth: true,
    title: 'Cache System',
    menu: true,
    menuTitle: 'Cache System',
    sort: 999999999,
)]
class Cache extends \Sokeio\Page
{
    use WithUI;
    protected function setupUI()
    {
        return [
            PageUI::init([])->rightUI([])
                ->title($this->getPageConfig()->getTitle())

        ];
    }
}
