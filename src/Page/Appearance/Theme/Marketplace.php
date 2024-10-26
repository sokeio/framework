<?php

namespace Sokeio\Page\Appearance\Theme;

use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\Input;
use Sokeio\UI\Field\UploadFile;
use Sokeio\UI\ModalUI;
use Sokeio\UI\WithUI;

#[PageInfo(admin: true, auth: true,  title: 'Marketplace Theme ')]
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
            ModalUI::init([
                Div::init()->className('mt-auto')->viewBlade('sokeio::pages.appearance.theme.marketplace')
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
                ->skipOverlayClose()

        ];
    }
}
