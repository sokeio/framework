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
    public function getModel(): string
    {
        return Item::class;
    }
    public function SelectGroupItem($group_item_id)
    {
        $this->data->group_item_id = $group_item_id;
    }
    public function formUI()
    {
        return UI::container([
            UI::prex(
                'data',
                [
                    UI::row([
                        UI::column12([
                            UI::text('name')->label(__('Name')),
                            UI::select('group_item_id')->label(__('Group Item'))->dataSource(function () {
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
                            })
                                ->beforeUI([
                                    UI::button(__('Add Group'))
                                        ->modalRoute('admin.group-item.create')
                                        ->modalTitle(__('Add Group Item')),
                                ])
                                ->afterUI([
                                    UI::template([
                                        UI::button(__('Edit Group'))
                                            ->modalTitle(__('Edit Group Item'))
                                            ->attribute(function () {
                                                $url = route('admin.group-item.create');
                                                return '
                                                x-bind:sokeio:modal="\'' . $url . '/\'+$wire.data.group_item_id"
                                                ';
                                            })
                                            ->modalTitle(__('Edit Group Item')),
                                    ])->attribute(' x-if="$wire.data.group_item_id>0" ')
                                ])->valueDefault(0),
                            UI::icon('icon')->label(__('Icon')),
                            UI::color('color')->label(__('Color')),
                            UI::textarea('description')->label(__('Description')),
                            UI::image('image')->label(__('Image'))
                        ])
                    ]),
                ]
            )
        ])
            ->className('p-3');
    }
}
