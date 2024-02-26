<?php

namespace Sokeio\Livewire\Item;

use Sokeio\Components\Form;
use Sokeio\Components\UI;
use Sokeio\Breadcrumb;
use Sokeio\Models\GroupItem;
use Sokeio\Models\Item;

class ItemForm extends Form
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
        return Item::class;
    }
    public function SelectGroupItem($group_item_id)
    {

        $this->data->group_item_id = $group_item_id;
        $this->data->name = "test";
        $this->refreshMe();
    }

    public function FormUI()
    {
        return UI::Container([
            UI::Prex(
                'data',
                [
                    UI::Row([
                        UI::Column12([
                            UI::Text('name')->Label(__('Name')),
                            UI::Select('group_item_id')->Label(__('Group Item'))->DataSource(function () {
                                return [
                                    [
                                        'id' => 0,
                                        'name' => __('None')
                                    ],
                                    ...GroupItem::all()->map(function ($item) {
                                        return [
                                            'id' => $item->id,
                                            'name' => $item->name
                                        ];
                                    })
                                ];
                            })->UIAfter([
                                UI::Button(__('Add Group'))->Attribute(' x-show="$wire.data.group_item_id==0" ')->ModalRoute('admin.group-item.create')->ModalTitle(__('Add Group Item')),
                                UI::Button(__('Edit Group'))->ModalTitle(__('Edit Group Item'))->Attribute(' x-show="$wire.data.group_item_id>0" x-bind:sokeio:modal="\'' . route('admin.group-item.create') . '/\'+$wire.data.group_item_id"')->ModalTitle(__('Edit Group Item')),
                            ])->ValueDefault('0'),
                            UI::Icon('icon')->Label(__('Icon')),
                            UI::Icon('color')->Label(__('Color')),
                            UI::Textarea('description')->Label(__('Description')),
                            UI::Image('image')->Label(__('Image'))
                        ])
                    ]),
                ]
            )
        ])
            ->ClassName('p-3');
    }
}
