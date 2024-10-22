<?php

namespace SokeioTheme\Admin\Page\SystemAccess\User;

use Sokeio\Models\User;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Field\Input;
use Sokeio\UI\ModalUI;
use Sokeio\UI\WithEditUI;

#[PageInfo(admin: true, auth: true,  title: 'User', model: User::class)]
class Edit extends \Sokeio\Page
{
    use WithEditUI;
    protected function setupUI()
    {
        return [
            ModalUI::init([
                Input::init('name')->label(__('Name')),
                Input::init('email')->label(__('Email')),
                Input::init('phone_number')->label(__('Phone')),
                Input::init('password')->label(__('Password'))->password()
                    ->when(function () {
                        return !$this->dataId;
                    }),
            ])->title(($this->dataId ? __('Edit') : __('Create')) . ' ' . $this->getPageConfig()->getTitle())
                ->className('p-2')->setPrefix('formData')
                ->afterUI([
                    Div::init([
                        Button::init()->text(__('Cancel'))->className('btn btn-warning me-2')->modalClose(),
                        Button::init()->text(__('Save'))->wireClick('saveData')
                    ])
                        ->className('px-2 pt-2 d-flex justify-content-end')
                ])
        ];
    }
}
