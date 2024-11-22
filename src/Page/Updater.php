<?php

namespace Sokeio\Page;

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
    public $wireTest="duwx dieux";

    protected function setupUI()
    {
        return [
            PageUI::init([
                Div::init()->viewBlade('sokeio::pages.system-updater')
            ])
                ->card()
                ->icon('ti ti-settings')

        ];
    }
    public function saveDemo(){
        $this->wireTest="duwx dieux:".rand(1, 100);
    }
}
