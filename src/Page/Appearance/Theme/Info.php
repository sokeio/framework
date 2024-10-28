<?php

namespace Sokeio\Page\Appearance\Theme;

use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Sokeio\Platform;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\Input;
use Sokeio\UI\ModalUI;
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
            ModalUI::init([
                Div::init()->className('mt-auto')->viewBlade(
                    'sokeio::pages.appearance.theme.info',
                    [
                        'theme' => Platform::theme()->find($this->dataId)
                    ]
                )
            ])->title($this->getPageConfig()->getTitle())
                ->className('p-2')
                ->xlSize()
                ->afterUI([
                    Div::init([
                        Button::init()->text(__('Cancel'))->className('btn btn-warning me-2')
                            ->modalClose()->icon('ti ti-x'),
                        Button::init()->text(__('Activate'))->icon('ti ti-create')
                    ])
                        ->className('px-2 pt-2 d-flex justify-content-end')
                ])

        ];
    }
}
