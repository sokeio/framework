<?php

namespace Sokeio\Page\Setting\Module;

use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Common\Button;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;

#[PageInfo(admin: true, auth: true,  title: 'Marketplace Module (Not Implemented)')]
class Marketplace extends \Sokeio\Page
{
    use WithUI;
    public function saveData()
    {
        $this->sokeioClose();
    }
    protected function setupUI()
    {
        return [
            PageUI::init([
                Div::init()->className('mt-auto')->viewBlade('sokeio::pages.setting.module.marketplace')
            ])->title($this->getPageConfig()->getTitle())
                ->className('p-2')
                ->afterUI([
                    Div::init([
                        Button::init()->text(__('Cancel'))->className('btn btn-warning me-2')
                            ->modalClose()->icon('ti ti-x')
                    ])
                        ->className('px-2 pt-2 d-flex justify-content-end')
                ])
                ->icon('ti ti-building-store')
                ->xxlSize()

        ];
    }
}
