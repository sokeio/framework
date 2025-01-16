<?php

namespace Sokeio\Content\Page\Appearance\Menu;

use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Sokeio\Attribute\PageInfo;
use Sokeio\Content\Enums\UIKey;
use Sokeio\Content\Models\Menu;
use Sokeio\Content\Models\MenuItem;
use Sokeio\Theme;
use Sokeio\UI\BaseUI;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Common\Tag;
use Sokeio\UI\Common\Card;
use Sokeio\UI\Common\None;
use Sokeio\UI\Field\CheckboxList;
use Sokeio\UI\Field\Input;
use Sokeio\UI\Field\Select;
use Sokeio\UI\PageUI;
use Sokeio\UI\SoUI;
use Sokeio\UI\WithUI;

#[PageInfo(
    admin: true,
    auth: true,
    title: 'Menu',
    menu: true,
    menuTitle: 'Menu',
    icon: 'ti ti-manual-gearbox',
    sort: 1
)]
class Index extends \Sokeio\Page
{
    use WithUI;
    public $formData = [];
    #[Url('menu')]
    public $menuId;
    public $menuName = "";
    public $menuItems = [];
    public $menuLocations = [];
    public function updatedMenuId($menuId)
    {
        $this->setMenuId($menuId);
    }
    public function setMenuId($menuId)
    {
        $this->menuLocations = [];
        $this->menuName = "";
        $this->menuItems = [];
        $this->menuId = $menuId;
        if ($menuId) {
            $menu = Menu::find($menuId);
            if (!$menu) {
                $this->menuId = null;
                return;
            }
            $this->menuLocations = $menu->locations ?? [];
            $this->menuName = $menu->name;
            $this->menuItems = collect($menu->items()->get())->map(function ($item) {
                return [
                    ...$item->toArray(),
                    'key_item' => 'item_' . $item->id
                ];
            })->keyBy('key_item')->toArray();
        }
        $this->reUI();
    }
    public function saveData()
    {
        $this->alert('Setting has been saved!', 'Setting', 'success');
    }
    public function updateItemMenu($id, $callback)
    {
        $menuItem = MenuItem::find($id) ?? new MenuItem();
        $callback($menuItem);
        $menuItem->menu_id = $this->menuId;
        $menuItem->save();
        $this->setMenuId($this->menuId);
    }
    public function removeItemMenu($id)
    {
        $menuItem = MenuItem::find($id);
        if ($menuItem) {
            MenuItem::query()->where('parent_id', $menuItem->id)->update(['parent_id' => $menuItem->parent_id]);
            $menuItem->delete();
        }
        $this->alert('Setting has been saved!', 'Setting', 'success');
        $this->setMenuId($this->menuId);
    }
    public function saveDataMenu()
    {
        $menu = Menu::find($this->menuId);
        $menu->locations = $this->menuLocations;
        $menu->name = $this->menuName;
        $menu->save();

        $this->alert('Setting Menu has been saved!', 'Setting', 'success');
    }
    public function deleteMenu()
    {
        $menu = Menu::find($this->menuId);
        $menu->delete();
        $this->menuId = null;
        $this->updatedMenuId($this->menuId);
    }
    public function mount()
    {
        $this->setMenuId($this->menuId);
    }
    public function menuUpdateOrder($items)
    {
        if (isset($items['value'])) {
            $items = [$items];
        }
        foreach ($items as $item) {
            $parent = $item['value'];
            $items = $item['items'];
            foreach ($items as $item) {
                MenuItem::where('id', $item['value'])->update(['order' => $item['order'], 'parent_id' => $parent ?? 0]);
            }
        }
        $this->setMenuId($this->menuId);
    }

    protected function menuItemUI($items, $parent = 0)
    {
        $groupItems = $items->where(fn($item) => $item['parent_id'] == $parent)->sortBy('order');
        return Div::make(
            $groupItems->map(function ($item) use ($items) {
                return Div::make([
                    None::make([
                        Div::make([
                            Div::make([
                                Tag::make()->i()->className('fs-2 ti ti-arrows-move'),
                            ])->className('cursor-pointer bg-primary text-bg-primary p-2')
                                ->attr(' wire:sortable-group.handle'),
                            Div::make(
                                Div::make([
                                    Tag::make()->i()->className($item['icon']),
                                    Tag::make()->a($item['url'])->xText('name')->className('ms-2'),
                                    Tag::make()->span()->className('flex-fill'),
                                    Tag::make()->span()->xText('type')
                                        ->className(' p-1 bg-warning text-bg-warning rounded-1'),
                                ])->className('d-flex align-items-center px-2')
                            )->className('flex-grow-1'),
                            Div::make([
                                Tag::make()->i()->className('fs-2 ti ti-arrow-narrow-down')->attr('x-show', '!showBox'),
                                Tag::make()->i()->className('fs-2 ti ti-arrow-narrow-up')->attr('x-show', 'showBox'),
                            ])->attr('@click', 'showBox = !showBox')
                                ->className('p-2 bg-danger text-bg-danger cursor-pointer'),
                        ])->className('d-flex align-items-center border rounded-1 overflow-hidden '),
                        Div::make()->className('p-2 border')
                            ->childWithKey(UIKey::MENU_ITEM_TYPE->value . $item['type'])
                            ->attr('style', 'display: none')
                            ->attr('x-show', 'showBox'),
                    ])->prefix('menuItems.item_' . $item['id']),
                    Div::make($this->menuItemUI($items, $item['id']))
                ])
                    ->wireKey('menu_item_' . $item['id'] . '-' . $item['parent_id'])
                    ->attr('wire:sortable-group.item')
                    ->attr('x-data', '{ showBox: false }')
                    ->attr('data-sortable-id', $item['id']);
            })
        )->className('pt-2 ' . ($parent ? 'ms-3' : ''))
            ->attr('wire:sortable-group')
            ->attr('wire:sortable-group.options', '{}')
            ->attr('wire:sortable-group.item-group', $parent);
    }
    protected function setupUI()
    {
        SoUI::debug('203199ef3c4bad7a8bac34e6ac05e7b8', function (BaseUI $ui, $key) {
            Log::info($key);
            Log::info($ui->getUIIDkey());
        });
        return [
            PageUI::make([
                Div::make([])->childWithGroupKey('content::menu_add_item')->prefix('formData')->col3(),
                Div::make([
                    Card::make([
                        Div::make([
                            Input::make('menuName')->label('Menu Name')
                                ->ruleRequired()
                                ->when(function () {
                                    return $this->menuId;
                                }),
                            CheckboxList::make('menuLocations')->label('Locations')
                                ->dataSource(Theme::getLocationOptions('menu')),
                            Div::make([
                                Div::make(Button::make()
                                    ->text('Delete')
                                    ->confirm('Are you sure?')
                                    ->wireClick('deleteMenu')
                                    ->className('btn btn-link p-2 mt-4'))
                                    ->colNone(),
                                Div::make()->colAuto(),
                                Div::make(
                                    Button::make()
                                        ->text('Save')
                                        ->wireClick('saveDataMenu')
                                        ->icon('ti ti-device-floppy')
                                        ->className('btn btn-warning p-2 mt-4 float-end')
                                )
                                    ->colNone(),
                            ])->row()
                                ->className('d-flex justify-content-end mb-3')
                                ->when(function () {
                                    return $this->menuId;
                                })

                        ])->col3(),
                        Div::make($this->menuItemUI(collect($this->menuItems)))
                            ->attr('wire:sortable-group')
                            ->attr('x-data', '{ onSortable(items) { $wire.menuUpdateOrder(items); } }')
                            ->col9(),

                    ])->bodyRow('p-2')
                        ->title('Menu')
                        ->hideSwitcher()
                        ->wireKey('menu::' . $this->menuId)

                ])->col9()
            ])
                ->row()
                ->rightUI(
                    [
                        Select::make('menuId')->remoteActionWithModel(Menu::class, 'name')->debounce(),
                        Button::make()->text('Add Menu')->className('btn btn-primary')
                            ->modal(function (Button $button) {
                                return route($this->getRouteName('create-menu'));
                            })
                    ]
                )
        ];
    }
}
