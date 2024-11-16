<?php

namespace Sokeio\Page;

use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;

#[PageInfo(
    admin: true,
    auth: true,
    title: 'System Updater',
    menu: true,
    menuTitle: 'System Updater',
    menuClass: 'sokeio-btn-system-updater',
    icon: 'ti ti-refresh fs-2',
    sort: 999999999999,
    enableKeyInSetting: 'SOKEIO_SYSTEM_UPDATER_ENABLE'
)]
class SystemUpdater extends \Sokeio\Page
{
    use WithUI;

    protected function setupUI()
    {
        return [
            PageUI::init([])
                ->icon('ti ti-settings')
                ->title($this->getPageConfig()->getTitle())

        ];
    }
}
