<?php

namespace Sokeio\Page;

use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Div;
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
    public $wireTest="duwx dieux";

    protected function setupUI()
    {
        return [
            PageUI::init([
                Div::init()->viewBlade('sokeio::pages.system-updater')
            ])
                ->card()
                ->icon('ti ti-settings')
                ->title($this->getPageConfig()->getTitle())

        ];
    }
}
