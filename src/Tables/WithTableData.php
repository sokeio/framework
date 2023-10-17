<?php

namespace BytePlatform\Tables;

use BytePlatform\DataForm;
use BytePlatform\ItemForm;
use BytePlatform\ItemForms;
use BytePlatform\Concerns\WithItemManager;
use BytePlatform\Concerns\WithPagination;

trait WithTableData
{
    use WithItemManager;
    use WithPagination;

    public DataForm $dataFilters;
    public DataForm $dataSorts;
    public ItemForms $formTable;
    public ItemForm $formSearch;

    public $selectIds = [];
    public $pageIds = [];
    public $pageSize = 15;
    public $checkAll = false;
    private const PAGE_SIZE = 15;
    public function Booted()
    {
        $this->dataFilters->___checkProperty = false;
        $this->dataSorts->___checkProperty = false;
        $this->formSearch->___checkProperty = false;
        if (!$this->selectIds) {
            $this->selectIds = request('selectIds') ?? [];
        }
        parent::Booted();
    }
    public function doSearch()
    {
        $this->ShowMessage(json_encode($this->formSearch));
    }
    public function mount()
    {
        $this->pageSize = $this->getItemManager()?->getPageSize(self::PAGE_SIZE) ?? self::PAGE_SIZE;
    }
    public function clearSort()
    {
        $this->dataSorts->Clear();
    }
    public function doReset()
    {
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
        if(!$query) return null;
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
        return view('byte::tables.index', [
            'itemManager' => $this->getItemManager(),
            'dataItems' => $this->getDataItems()
        ]);
    }
}
