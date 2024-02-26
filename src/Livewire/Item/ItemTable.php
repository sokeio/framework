<?php

namespace Sokeio\Livewire\Item;

use Sokeio\Models\Item ;
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
    // $table->integer('group_item_id')->nullable();
    // $table->string('name', 255)->nullable();
    // $table->string('icon', 255)->nullable();
    // $table->string('description', 400)->nullable()->default('');
    // $table->longText('content')->nullable();
    // $table->string('image', 255)->nullable();
    public function getColumns()
    {
        return [
            UI::Text('name')->Label(__('Name')),
            UI::Text('group_item_id')->Label(__('Group Item')),
            UI::Text('icon')->Label(__('Icon'))->NoSort(),
            UI::Text('description')->Label(__('Description'))->NoSort(),
            UI::Text('image')->Label(__('Image'))->NoSort(),
            UI::Text('created_at')->Label(__('Created At')),
            UI::Text('updated_at')->Label(__('Updated At')),
        ];
    }
}
