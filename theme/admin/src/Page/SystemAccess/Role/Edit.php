<?php

namespace SokeioTheme\Admin\Page\SystemAccess\Role;

use Illuminate\Support\Facades\Log;
use Sokeio\Models\Role;
use Sokeio\Attribute\PageInfo;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\Checkbox;
use Sokeio\UI\Field\Input;
use Sokeio\UI\Field\LivewireField;
use Sokeio\UI\Field\Textarea;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithEditUI;

#[PageInfo(admin: true, auth: true,  title: 'Role', model: Role::class)]
class Edit extends \Sokeio\Page
{
    use WithEditUI;
    private $isSuperAdmin = null;
    protected function afterMount($data)
    {
        $permissions = $data->permissions()->pluck('id')->toArray();
        data_set($this->formData, 'permissions', $permissions);
    }
    protected function afterSaveData($data)
    {
        $permissions = data_get($this->formData, 'permissions');
        $data->permissions()->sync($permissions);
    }
    protected function getIsAdmin()
    {
        if ($this->isSuperAdmin === null&&$this->dataId) {
            $this->isSuperAdmin = $this->getDataModel()->isSuperAdmin();
        }
        return $this->isSuperAdmin;
    }
    public function getPageTitle()
    {
        return (($this->dataId ? __('Edit') : __('Create')) . ' ' . $this->getPageConfig()->getTitle())
            . ($this->getIsAdmin() ? ' (Super Admin)' : '');
    }

    protected function setupUI()
    {
        return [
            PageUI::make([
                Input::make('name')->label(__('Name'))->ruleRequired(),
                Input::make('slug')->label(__('Slug'))->ruleRequired()->ruleUnique()->when(function () {
                    return !$this->getIsAdmin();
                }),
                Textarea::make('description')->label(__('Description')),
                Checkbox::make('is_active')->label(__('Status'))->labelCheckbox(__('Active'))
                    ->when(function () {
                        return !$this->getIsAdmin();
                    }),
                LivewireField::make('permissions')
                    ->skipFill()
                    ->component('sokeio::permission-list.index')->label(__('Permissions'))
                    ->when(function () {
                        return !$this->getIsAdmin();
                    }),
            ])->onlyModal()
                ->prefix('formData')
                ->afterUI([
                    Div::make([
                        Button::make()->label(__('Cancel'))->className('btn btn-warning me-2')->modalClose(),
                        Button::make()->label(__('Save'))->wireClick('saveData')
                    ])->useModalButtonRight()
                ])
        ];
    }
}
