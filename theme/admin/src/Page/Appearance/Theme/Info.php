<?php

namespace SokeioTheme\Admin\Page\Appearance\Theme;

use Livewire\Attributes\Url;
use Sokeio\Platform;
use Sokeio\Attribute\AdminPageInfo;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Common\Button;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;

#[AdminPageInfo(title: 'Theme Info')]
class Info extends \Sokeio\Page
{
    use WithUI;
    #[Url(as: 'id')]
    public $dataId;
    protected function setupUI()
    {
        return [
            PageUI::make([
                Div::make()->className('mt-auto')->viewBlade(
                    'sokeio::livewire.platform.info',
                    [
                        'item' => Platform::theme()->find($this->dataId)
                    ]
                )
            ])
                ->xlSize()
                ->afterUI([
                    Div::make([
                        Button::make()->label(__('Activate'))->icon('ti ti-checks')
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
