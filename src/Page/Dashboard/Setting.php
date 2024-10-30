<?php

namespace Sokeio\Page\Dashboard;

use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Common\Div;
use Sokeio\UI\ModalUI;
use Sokeio\UI\WithUI;

#[PageInfo(
    admin: true,
    auth: true,
    title: 'Dashboard Setting',
    route: 'dashboard.setting',
)]
class Setting extends \Sokeio\Page
{
    use WithUI;

    protected function setupUI()
    {
        return ModalUI::init([])->title($this->getPageConfig()->getTitle())
            ->icon('ti ti-settings')
            ->className('p-2')
            ->fullscreenSize()
            ->afterUI([
                Div::init([
                    Button::init()->text(__('Cancel'))->className('btn btn-warning me-2')
                        ->modalClose()->icon('ti ti-x'),
                    Button::init()->text(__('Create'))->wireClick('saveData')->icon('ti ti-device-floppy')
                ])
                    ->className('px-2 pt-2 d-flex justify-content-end')
            ]);
    }
}
