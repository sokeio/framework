<?php

namespace Sokeio\Page;

use Illuminate\Support\Facades\Cache;
use Sokeio\Attribute\PageInfo;
use Sokeio\UI\Common\Div;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;

#[PageInfo(
    admin: true,
    auth: true,
    title: 'Updater',
    menu: true,
    menuTitle: 'Updater',
    menuClass: 'sokeio-btn-system-updater',
    icon: 'ti ti-refresh fs-2',
    sort: 999999999999,
    enableKeyInSetting: 'SOKEIO_SYSTEM_UPDATER_ENABLE'
)]
class Updater extends \Sokeio\Page
{
    use WithUI;
    public $wireTest = "duwx dieux";
    public $keyCache = 'abc';

    protected function setupUI()
    {
        return [
            PageUI::init([
                Div::init()->viewBlade('sokeio::pages.system-updater')
            ])
                ->hidePageHeader()
                ->card()
                ->icon('ti ti-settings')
                ->className('page-system-updater')

        ];
    }
    public function systemUpdater()
    {
        $this->wireTest = "duwx dieux:" . date('Y-m-d H:i:s');
        $this->js('setTimeout(() => { $wire.systemUpdater(); }, 1000);');
    }
}
