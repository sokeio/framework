<?php

namespace Sokeio\Livewire\Role;

use Sokeio\Components\Common\Tab;
use Sokeio\Components\Form;
use Sokeio\Components\UI;
use Sokeio\Breadcrumb;
use Sokeio\Models\Permission;
use Sokeio\Models\Role;

class RoleForm extends Form
{
    public $permissionids = [];
    public function getTitle()
    {
        return __('Role');
    }
    public function getBreadcrumb()
    {
        return [
            Breadcrumb::Item(__('Home'), route('admin.dashboard'))
        ];
    }
    public function getButtons()
    {
        return [];
    }
    public function getModel()
    {
        return Role::class;
    }

    protected function loadDataAfter($role)
    {
        $this->permissionids = $role->PermissionIds;
    }
    protected function saveAfter($role)
    {
        $role->permissions()->sync(collect($this->permissionids)->filter(function ($item) {
            return $item > 0;
        })->toArray());
    }
    public function formUI()
    {
        return UI::container([
            UI::prex(
                'data',
                [
                    UI::row([
                        UI::column6([
                            UI::text('name')->label(__('Role Name'))->required()
                        ]),
                        UI::column6([
                            UI::text('slug')->label(__('Role Slug'))
                        ]),
                        UI::column6([
                            UI::checkBox('status')->label(__('Role Status'))->title(__('Active'))
                        ]),
                    ]),
                ]
            ),
            UI::row([
                UI::column12([
                    UI::checkBoxMutil('permissionids')->label(__('Permission'))->dataSource(function () {
                        return Permission::all();
                    })->noSave()
                ]),
            ])
        ])

            ->className('p-3');
    }
}
