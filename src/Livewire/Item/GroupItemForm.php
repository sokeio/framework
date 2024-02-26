<?php

namespace Sokeio\Livewire\Item;

use Sokeio\Components\Form;
use Sokeio\Components\UI;
use Sokeio\Breadcrumb;
use Sokeio\Models\GroupItem;

class GroupItemForm extends Form
{
    public function getTitle()
    {
        return __('Item');
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
        return GroupItem::class;
    }

    public function refreshRefComponent()
    {
        $this->callFuncByRef('SelectGroupItem', $this->dataId);
    }
    public function FormUI()
    {
        return UI::Container([
            UI::Prex(
                'data',
                [
                    UI::Text('name')->Label(__('Name')),
                ]
            )
        ])
            ->ClassName('p-3');
    }
}
