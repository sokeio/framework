<?php

namespace SokeioTheme\Admin\Page\SystemAccess\User;

use Illuminate\Support\Facades\Log;
use Sokeio\Models\Role;
use Sokeio\Models\User;
use Sokeio\Core\Attribute\AdminPageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Field\Input;
use Sokeio\UI\Field\LivewireField;
use Sokeio\UI\Field\Password;
use Sokeio\UI\Field\Select;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithEditUI;

#[AdminPageInfo(title: 'User', model: User::class)]
class Edit extends \Sokeio\Page
{
    use WithEditUI;
    protected function afterMount($data)
    {
        $permissions = $data->permissions()->pluck('id')?->toArray();
        $roles = $data->roles()->pluck('id')?->toArray();
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
        $data->roles()->sync($roles);
        $data->permissions()->sync($permissions);
    }
    protected function setupUI()
    {
        return [
            PageUI::make([
                Input::make('name')->label(__('Name'))->ruleRequired('Please enter name'),
                Input::make('email')->label(__('Email'))->ruleRequired()->ruleEmail(),
                Input::make('phone_number')->label(__('Phone'))->ruleRequired(),
                Password::make('password')->label(__('Password'))
                    ->when(function () {
                        return !$this->dataId;
                    }),
                Select::make('roles')->multiple()->label(__('Role'))->remoteActionWithModel(Role::class)->skipFill(),
                LivewireField::make('permissions')
                    ->lazy()
                    ->skipFill()
                    ->component('sokeio::permission-list.index')->label(__('Permissions'))
            ])
                ->onlyModal()
                ->prefix('formData')
                ->afterUI([
                    Div::make([
                        Button::make()->label(__('Cancel'))->className('btn btn-warning me-2')->modalClose(),
                        Button::make()->label(__('Save'))->wireClick('saveData'),
                    ])->useModalButtonRight()
                ])
                ->icon('ti ti-users')
        ];
    }
}
