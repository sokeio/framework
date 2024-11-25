<?php

namespace SokeioTheme\Admin\Page\SystemAccess\User;

use Illuminate\Support\Facades\Log;
use Sokeio\Models\Role;
use Sokeio\Models\User;
use Sokeio\Attribute\PageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Field\Input;
use Sokeio\UI\Field\LivewireField;
use Sokeio\UI\Field\Select;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithEditUI;

#[PageInfo(admin: true, auth: true,  title: 'User', model: User::class)]
class Edit extends \Sokeio\Page
{
    use WithEditUI;
    protected function afterMount($data)
    {
        $permissions = $data->permissions()->pluck('id')?->toArray() ;
        $roles = $data->roles()->pluck('id')?->toArray() ;
        data_set($this->formData, 'permissions', $permissions);
        data_set($this->formData, 'roles', $roles);
    }
    protected function beforeSaveData($data)
    {
        if ($data->password === null) {
            $data->password = $data->getOriginal('password');
        }
        return $data;
    }
    protected function afterSaveData($data)
    {
        $permissions = data_get($this->formData, 'permissions');
        $roles = data_get($this->formData, 'roles');
        Log::info(['afterSaveData', $permissions, $roles]);
        $data->roles()->sync($roles);
        $data->permissions()->sync($permissions);
    }
    protected function setupUI()
    {
        return [
            PageUI::init([
                Input::init('name')->label(__('Name'))->ruleRequired('Please enter name'),
                Input::init('email')->label(__('Email'))->ruleRequired()->ruleEmail(),
                Input::init('phone_number')->label(__('Phone'))->ruleRequired(),
                Input::init('password')->label(__('Password'))->password()
                    ->when(function () {
                        return !$this->dataId;
                    }),
                Select::init('roles')->multiple()->label(__('Role'))->remoteActionWithModel(Role::class)->skipFill(),
                LivewireField::init('permissions')
                    ->lazy()
                    ->skipFill()
                    ->component('sokeio::permission-list.index')->label(__('Permissions'))
            ])
                ->onlyModal()
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
