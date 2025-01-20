<?php

namespace Sokeio\Page\Appearance\Menu;

use Sokeio\Attribute\PageInfo;
use Sokeio\Models\Menu;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Field\Input;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithEditUI;

#[PageInfo(admin: true, auth: true,  title: 'Menu', icon: 'ti ti-menu', model: Menu::class)]
class CreateMenu extends \Sokeio\Page
{
    use WithEditUI;
    protected function afterSaveData($data)
    {
        $this->callFuncByRef('updatedMenuId2', [$data['id']]);
    }
    protected $skipByUser = true;
    protected function setupUI()
    {
        return [
            PageUI::make([
                Input::make('name')->label(__('Name'))->ruleRequired(),
            ])
                ->prefix('formData')
                ->smSize()
                ->afterUI([
                    Div::make([
                        Button::make()->text(__('Cancel'))->className('btn btn-warning me-2')->modalClose(),
                        Button::make()->text(__('Save'))->wireClick('saveData'),
                    ])
                        ->className('px-2 pt-2 d-flex justify-content-end')
                ])
        ];
    }
}
