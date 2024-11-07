<?php

namespace Sokeio\Page\Setting;

use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;

#[PageInfo(
    admin: true,
    auth: true,
    title: 'Social Login',
    menu: true,
    menuTitle: 'Social Login',
    icon: 'ti ti-social',
    sort: 999999999,
)]
class SocialLogin extends \Sokeio\Page
{
    use WithUI;
    protected function setupUI()
    {
        return [
            PageUI::init([])->rightUI([])
            ->icon('ti ti-social')
                ->title($this->getPageConfig()->getTitle())

        ];
    }
}
