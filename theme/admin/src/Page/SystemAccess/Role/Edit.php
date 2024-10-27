<?php

namespace SokeioTheme\Admin\Page\SystemAccess\Role;

use Sokeio\Models\Role;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\Checkbox;
use Sokeio\UI\Field\Input;
use Sokeio\UI\Field\Textarea;
use Sokeio\UI\ModalUI;
use Sokeio\UI\WithEditUI;

#[PageInfo(admin: true, auth: true,  title: 'Role', model: Role::class)]
class Edit extends \Sokeio\Page
{
    use WithEditUI;
    protected function setupUI()
    {
        return [
            ModalUI::init([
                Input::init('name')->label(__('Name')),
                Input::init('slug')->label(__('Slug')),
                Textarea::init('description')->label(__('Description')),
                Checkbox::init('is_active')->label(__('Status'))->labelCheckbox(__('Active')),
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
