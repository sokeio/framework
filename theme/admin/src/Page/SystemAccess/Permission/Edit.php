<?php

namespace SokeioTheme\Admin\Page\SystemAccess\Permission;

use Sokeio\Models\Permission;
use Sokeio\Core\Attribute\AdminPageInfo;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\Input;
use Sokeio\UI\Field\Textarea;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithEditUI;

#[AdminPageInfo(title: 'Permission', model: Permission::class)]
class Edit extends \Sokeio\Page
{
    use WithEditUI;
    protected function setupUI()
    {
        return [
            PageUI::make([
                Input::make('name')->label(__('Name'))->ruleRequired(),
                Input::make('slug')->label(__('Slug'))->ruleRequired(),
                Input::make('group')->label(__('Group'))->ruleRequired(),
                Textarea::make('description')->label(__('Description')),
            ])
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
