<?php

namespace SokeioTheme\Admin\Page\SystemAccess\User;

use Sokeio\Models\Role;
use Sokeio\Models\User;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Field\Input;
use Sokeio\UI\Field\Select;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithEditUI;

#[PageInfo(admin: true, auth: true,  title: 'User', model: User::class)]
class Edit extends \Sokeio\Page
{
    use WithEditUI;
    protected function setupUI()
    {
        return [
            PageUI::init([
                Input::init('name')->label(__('Name'))->ruleRequired('Please enter name'),
                Input::init('email')->label(__('Email'))->ruleRequired()->ruleEmail(),
                Input::init('phone_number')->label(__('Phone'))->ruleRequired()->rulePhone(),
                Input::init('password')->label(__('Password'))->password()
                    ->when(function () {
                        return !$this->dataId;
                    }),
                Select::init('role_id')->label(__('Role'))->remoteActionWithModel(Role::class)->when(function () {
                    return !$this->dataId;
                }),
            ])
                ->onlyModal()
                ->title($this->getTitleForm())
                ->className('p-2')->setPrefix('formData')
                ->afterUI([
                    Div::init([
                        Button::init()->text(__('Cancel'))->className('btn btn-warning me-2')->modalClose(),
                        Button::init()->text(__('Save'))->wireClick('saveData'),
                    ])->useModalButtonRight()
                ])
                ->icon('ti ti-users')
        ];
    }
}
