<?php

namespace Sokeio\Livewire\Item;

use Sokeio\Models\Item;
use Sokeio\Components\Table;
use Sokeio\Components\UI;

class ItemTable extends Table
{
    protected function getModel()
    {
        return Item::class;
    }
    public function getTitle()
    {
        return __('Item');
    }
    protected function getRoute()
    {
        return 'admin.item';
    }

    protected function getQuery()
    {
        return parent::getQuery()->with(['group']);
    }
    public function getColumns()
    {
        return [
            UI::text('name')->label(__('Name')),
            UI::text('group_item_id')->label(__('Group Item'))->fieldValue(function ($item) {

                if ($item->group) {
                    return $item->group->name;
                }
                return __('None');
            }),
            UI::text('icon')->label(__('Icon'))->NoSort(),
            UI::text('description')->label(__('Description'))->NoSort(),
            UI::text('image')->label(__('Image'))->NoSort(),
            UI::text('created_at')->label(__('Created At')),
            UI::text('updated_at')->label(__('Updated At')),
        ];
    }
}
