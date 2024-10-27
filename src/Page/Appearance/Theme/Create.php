<?php

namespace Sokeio\Page\Appearance\Theme;

use Livewire\Attributes\Rule;
use Sokeio\Platform;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\Input;
use Sokeio\UI\ModalUI;
use Sokeio\UI\WithUI;

#[PageInfo(admin: true, auth: true,  title: 'Create Theme ')]
class Create extends \Sokeio\Page
{
    use WithUI;
    #[Rule('required')]
    public $themeName = '';
    public function saveData()
    {
        $this->validate();
        Platform::theme()->generate($this->themeName);
        $this->sokeioClose();
        $this->refreshRef();
    }
    protected function setupUI()
    {
        return [
            ModalUI::init([
                Input::init('themeName')->label(__('Name')),
            ])->title($this->getPageConfig()->getTitle())
                ->className('p-2')
                ->smSize()
                ->afterUI([
                    Div::init([
                        Button::init()->text(__('Cancel'))->className('btn btn-warning me-2')
                            ->modalClose()->icon('ti ti-x'),
                        Button::init()->text(__('Create'))->wireClick('saveData')->icon('ti ti-create')
                    ])
                        ->className('px-2 pt-2 d-flex justify-content-end')
                ])

        ];
    }
}
