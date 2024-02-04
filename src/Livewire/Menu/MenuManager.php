<?php

namespace Sokeio\Livewire\Menu;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Sokeio\Breadcrumb;
use Sokeio\Models\Menu;
use Sokeio\Models\MenuLocation;
use Sokeio\Component;
use Sokeio\Facades\Assets;
use Sokeio\Facades\Theme;

class MenuManager extends Component
{
    public $menu_locations = [];
    public $menu_lists = [];
    #[Url]
    public $locationId = 0;
    public function updated($propertyName, $newValue)
    {
        if ($propertyName === 'locationId') {
            $this->loadMenu($newValue);
        }
        if (str_starts_with($propertyName, 'menu_locations.')) {
            if (!$this->locationId) {
                $this->showMessage('Please select menu');
                return false;
            }
            $location = MenuLocation::find($this->locationId);
            $location->locations = array_keys(array_filter($this->menu_locations, function ($item) {
                return $item;
            }));
            $location->save();
        }
    }
    public function __loadData()
    {
        $this->loadMenu($this->locationId);
    }
    public function loadMenu($locationId)
    {
        $this->locationId = $locationId;
        $location = MenuLocation::find($locationId);
        $this->menu_locations = [];
        $this->menu_lists = [];
        if ($location) {
            $temp_locations = $location->locations;
            if ($temp_locations) {
                foreach ($temp_locations  as $item) {
                    $this->menu_locations[$item] = true;
                }
            }
            $this->menu_lists = Menu::where('menu_location_id', $this->locationId)->get()->toArray();
        }
    }
    protected function getBreadcrumb()
    {
        return [
            Breadcrumb::Item(__('Home'), route('admin.dashboard'))
        ];
    }
    protected function getTitle()
    {
        return 'Menu Manager';
    }
    public function mount()
    {
        Assets::setTitle($this->getTitle());
        breadcrumb()->Title($this->getTitle())->Breadcrumb($this->getBreadcrumb());
        $this->loadMenu($this->locationId);
    }
    private function updateSortMenu($data_list, $items, $parent_id)
    {
        foreach ($items as $item) {
            $value = $item['value'];
            $order = $item['order'];
            if (!isset($data_list[$value]))
                $data_list[$value] = [
                    'order' => $order,
                    'parent_id' => $parent_id
                ];
            if (isset($item['items']) && count($item['items']) > 0) {
                $_items = $item['items'];
                $data_list = $this->updateSortMenu($data_list, $_items, $value);
            }
        }
        return $data_list;
    }
    public function doUpdateSortMenu($items)
    {
        $menu_lists2 = $this->updateSortMenu([], $items, 0);
        $data_list = [];
        foreach ($this->menu_lists as $item) {
            if (isset($menu_lists2[$item['id']])) {
                $data_list[] = [
                    ...$item,
                    ...$menu_lists2[$item['id']]
                ];
            } else {
                $data_list[] = [
                    ...$item
                ];
            }
        }
        $this->menu_lists = $data_list;
        foreach ($this->menu_lists as $item) {
            Menu::where('id', $item['id'])->update([
                'parent_id' => $item['parent_id'],
                'order' => $item['order']
            ]);
        }
        // $this->showMessage(json_encode($this->menu_lists));
        // $this->skipRender();
    }
    public function removeMenu($id)
    {
        $this->menu_lists = collect($this->menu_lists)->where(function ($item) use ($id) {
            return $item['id'] != $id;
        })->toArray();
    }
    public function doDeleteMenu()
    {
        $this->menu_lists = [];
        DB::transaction(function () {
            Menu::where('menu_location_id', $this->locationId)->delete();
            MenuLocation::where('id', $this->locationId)->delete();
        });
        $this->loadMenu(0);
    }
    public function doAddMenu($data)
    {
        if (!$this->locationId) {
            $this->showMessage('Please select menu');
            return false;
        }
        $menuItem = new Menu();
        $menuItem->menu_location_id = $this->locationId;
        $menuItem->parent_id = 0;
        foreach (['icon', 'name', 'color', 'link', 'attr_name', 'class_name', 'data_type', 'data'] as $key) {
            if (isset($data[$key])) {
                $menuItem->$key = $data[$key];
            }
        }
        $menuItem->order = 0;
        $menuItem->save();
        $this->menu_lists[] = [
            'id' => $menuItem->id,
            'parent_id' => 0,
            'order' => 0,
            ...$data
        ];
        $this->__loadData();
        return true;
    }
    public function render()
    {
        return view('sokeio::menu.index', [
            'locations' => Theme::getLocations(),
            'MenuLocation' => MenuLocation::all()
        ]);
    }
}
