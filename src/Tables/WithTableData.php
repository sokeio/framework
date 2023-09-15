<?php

namespace BytePlatform\Tables;

use BytePlatform\DataForm;
use BytePlatform\ItemForms;
use BytePlatform\Traits\WithItemManager;
use BytePlatform\Traits\WithPagination;

trait WithTableData
{
    use WithItemManager;
    use WithPagination;

    public DataForm $dataFilters;
    public DataForm $dataSorts;
    public ItemForms $formTable;

    public $selectIds = [];
    public $pageIds = [];
    public $pageSize = 15;
    public $checkAll = false;
    public function Booted()
    {
        $this->dataFilters->___checkProperty = false;
        $this->dataSorts->___checkProperty = false;
        if (!$this->selectIds) {
            $this->selectIds = request('selectIds') ?? [];
        }
        parent::Booted();
    }
    public function mount()
    {
        $this->pageSize = $this->getItemManager()->getPageSize(15);
    }
    public function clearSort()
    {
        $this->dataSorts->Clear();
    }
    public function clearFilter()
    {
        $this->dataFilters->Clear();
    }
    public function resetSelectIds()
    {
        $this->selectIds = [];
        $this->checkAll = false;
    }
    public function getDataSelectItem()
    {
        $this->skipRender();
        return $this->getQuery()->whereIn('id', $this->selectIds)->get();
    }
    public function doFilter($field, $value)
    {
        $this->dataFilters->{$field} = $value;
    }
    public function doSort($field)
    {
        $flg = $this->dataSorts->{$field};
        $this->dataSorts->Clear();
        $this->dataSorts->{$field} = !!$flg ? 0 : 1;
    }
    public function getDataItems()
    {
        $query = $this->getQuery();
        $arrSort = $this->dataSorts->toArray();
        if (isset($arrSort)) {
            foreach ($arrSort as $key => $value) {
                if ($value == 1) {
                    $query->orderBy($key, 'desc');
                } else {
                    $query->orderBy($key, 'asc');
                }
            }
        }
        $arrFilters = $this->dataFilters->toArray();
        if (isset($arrFilters)) {
            foreach ($arrFilters as $key => $value) {
                if ($key && $value) {
                    $query->where($key, $value);
                }
            }
        }
        $data = $query->paginate($this->pageSize, pageName: $this->getItemManager()->getPageName());
        $this->pageIds = collect($data->items())->map(function ($item) {
            return $item->id;
        });
        if ($this->getItemManager()->getEditInTable()) {
            $this->formTable->BindData($data);
        }
        return $data;
    }
    public function paginationView()
    {
        return 'byte::pagination';
    }
    public function render()
    {
        page_title($this->getItemManager()->getTitle());
        return view('byte::tables.index', [
            'itemManager' => $this->getItemManager(),
            'dataItems' => $this->getDataItems()
        ]);
    }
}
