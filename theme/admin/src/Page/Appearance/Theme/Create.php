<?php

namespace SokeioTheme\Admin\Page\Appearance\Theme;

use Livewire\Attributes\Rule;
use Sokeio\Platform;
use Sokeio\Attribute\AdminPageInfo;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\Input;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;

#[AdminPageInfo(admin: true, auth: true,  title: 'Create Theme ')]
class Create extends \Sokeio\Page
{
    use WithUI;
    #[Rule('required')]
    public $name = '';
    public function saveData()
    {
        $this->validate();
        Platform::theme()->generate($this->name);
        $this->sokeioClose();
        $this->refreshRef();
    }
    protected function setupUI()
    {
        return [
            PageUI::make([
                Input::make('name')->label(__('Name')),
            ])
                
                ->smSize()
                ->afterUI([
                    Div::make([
                        Button::make()->label(__('Cancel'))->className('btn btn-warning me-2')
                            ->modalClose()->icon('ti ti-x'),
                        Button::make()->label(__('Create'))->wireClick('saveData')->icon('ti ti-create')
                    ])
                        ->className('px-2 pt-2 d-flex justify-content-end')
                ])

        ];
    }
}
