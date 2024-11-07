<?php

namespace SokeioTheme\Admin\Page\SystemAccess\Permission;

use Sokeio\Models\Permission;
use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\Input;
use Sokeio\UI\Field\Textarea;
use Sokeio\UI\ModalUI;
use Sokeio\UI\WithEditUI;

#[PageInfo(admin: true, auth: true,  title: 'Permission', model: Permission::class)]
class Edit extends \Sokeio\Page
{
    use WithEditUI;
    protected function setupUI()
    {
        return [
            ModalUI::init([
                Input::init('name')->label(__('Name'))->ruleRequired(),
                Input::init('slug')->label(__('Slug'))->ruleRequired(),
                Input::init('group')->label(__('Group'))->ruleRequired(),
                Textarea::init('description')->label(__('Description')),
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
