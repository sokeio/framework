<?php

namespace Sokeio\Page\Appearance\Theme;

use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Sokeio\Platform;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\Input;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;

#[PageInfo(admin: true, auth: true,  title: ' Theme Info')]
class Info extends \Sokeio\Page
{
    use WithUI;
    #[Url(as: 'id')]
    public $dataId;
    protected function setupUI()
    {
        return [
            PageUI::init([
                Div::init()->className('mt-auto')->viewBlade(
                    'sokeio::pages.appearance.theme.info',
                    [
                        'item' => Platform::theme()->find($this->dataId)
                    ]
                )
            ])->title($this->getPageConfig()->getTitle())
                ->className('p-2')
                ->xlSize()
                ->afterUI([
                    Div::init([
                        Button::init()->text(__('Activate'))->icon('ti ti-checks')
                            ->wireClick(function () {
                                Platform::theme()->setActive($this->dataId);
                                $this->sokeioClose();
                                $this->refreshRef();
                            }, 'btn_theme_activate')
                    ])
                        ->className('px-2 pt-2 d-flex justify-content-end')
                ])

        ];
    }
}
