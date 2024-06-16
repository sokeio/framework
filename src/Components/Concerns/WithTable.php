<?php

namespace Sokeio\Components\Concerns;

use Sokeio\Components\Field\BaseField;
use Sokeio\Components\UI;
use Sokeio\Facades\Assets;
use Sokeio\Form;

trait WithTable
{
    use WithModelQuery, WithTablePagination;
    use WithLayoutUI;

    public $lazyloadingTable = true;
    public $searchWithFields = ['title'];
    private $searchlayout;
    private $tablecolumns;
    private $tableActions;
    // #[Url]
    public $selectIds = [];
    public $textSearch = '';
    public Form $search;
    public Form $orderBy;
    public $pageSize;

    protected function getPageName()
    {
        return 'page';
    }
    protected function getDefaultPageSize()
    {
        return setting("SOKEIO_TABLE_PAGE_SIZE", 10);
    }
    protected function getPageSize()
    {
        return [5, 10, 15, 30, 50, 100];
    }

    protected function searchFields()
    {
        return $this->searchWithFields;
    }
    public function doSort($name)
    {
        if (isset($this->orderBy->{$name})) {
            $this->orderBy->{$name} = $this->orderBy->{$name} == 0;
        } else {
            $this->orderBy->clear();
            $this->orderBy->{$name} = 1;
        }
    }
    public function doRemove($id)
    {
        // Retrieve the record you want to delete
        $record = $this->getQuery()->find($id);

        if ($record) {
            if (method_exists($this, 'removeBefore') && !$this->removeBefore($record)) {
                return;
            }
            // Delete the record
            $record->delete();

            if (method_exists($this, 'removeAfter') && !$this->removeAfter($record)) {
                return;
            }
            // Record successfully deleted
            $this->showMessage(__("The record has been deleted successfully."));
        } else {
            // Record not found
            $this->showMessage(__("The record does not exist."));
        }
    }
    protected function getRoute()
    {
        return '';
    }
    protected function getModalTitle($isNew = true, $row = null)
    {
        if ($isNew) {
            return __('Create ' . $this->getTitle() ?? 'Form');
        } else {
            return __('Edit ' . $this->getTitle() ?? 'Form');
        }
    }
    protected function getModalSize($isNew = true, $row = null)
    {
        return UI::MODAL_LARGE;
    }
    protected function getButtons()
    {
        return [
            UI::buttonCreate(__(''))
                ->icon('<i class="ti ti-circle-plus fs-2"></i>')
                ->modalRoute($this->getRoute() . '.add')
                ->modalTitle(function () {
                    return $this->getModalTitle();
                })
                ->modalSize(function () {
                    return $this->getModalSize();
                })
        ];
    }

    //The record has been deleted successfully.
    protected function getTableActions()
    {
        return [
            UI::buttonEdit(__(''))
                ->icon('<i class="ti ti-edit fs-2"></i>')
                ->modalRoute($this->getRoute() . '.edit', function ($row) {
                    return [
                        'dataId' => $row->id
                    ];
                })->modalTitle(function ($row) {
                    return $this->getModalTitle(false, $row);
                })->modalSize(function ($row) {
                    return $this->getModalSize(false, $row);
                }),
            UI::buttonRemove(__(''))
                ->icon('<i class="ti ti-trash fs-2"></i>')
                ->confirm(__('Do you want to delete this record?'), 'Confirm')
                ->wireClick(function ($item) {
                    return 'doRemove(' . $item->getDataItem()->id . ')';
                })
        ];
    }
    public function doSearch()
    {
        return null;
    }
    protected function searchUI()
    {
        return null;
    }
    protected function showSearchUI()
    {
        return false;
    }
    protected function initLayout()
    {
        if (!$this->pageSize) {
            $this->pageSize = $this->getDefaultPageSize() ?? 10;
        }
        if (!$this->searchlayout && ($_search = $this->searchUI())) {
            $this->searchlayout = $this->reLayout(UI::prex('search', $_search));
        }
        if (!$this->tableActions) {
            $this->tableActions = $this->reLayout($this->getTableActions());
        }
        if (!$this->tablecolumns) {
            $this->tablecolumns = $this->reLayout($this->getColumns());
        }
    }

    protected function getView()
    {
        if ($this->currentIsPage()) {
            $this->doBreadcrumb();
            return 'sokeio::components.table.page';
        }
        return 'sokeio::components.table.index';
    }
    protected function queryOperator($query)
    {
        $operator = $this->search->toArray();
        return BaseField::operatorQuery($query, $operator);
    }
    protected function queryOrder($query)
    {
        $orderBy = $this->orderBy->toArray();
        if (count(($orderBy))) {
            foreach ($orderBy as $key => $value) {
                if ($value == 1) {
                    $query->orderBy($key, 'desc');
                } else {
                    $query->orderBy($key, 'asc');
                }
            }
        }
        return $query;
    }
    protected function getData()
    {
        if ($this->lazyloadingTable) {
            return null;
        }
        $query = $this->getQuery();
        if ($textSearch = $this->textSearch) {
            $query->orWhere(function ($subquery) use ($textSearch) {
                foreach ($this->searchFields() as $field) {
                    if (!$field) {
                        continue;
                    }
                    $arrFields = explode('.', $field);
                    if (count($arrFields) == 1) {
                        $subquery->orWhere($field, 'like', '\'%' . $textSearch . '%\'');
                    } else {
                        if (!$arrFields[0]) {
                            continue;
                        }
                        $subquery->orWhereHas($arrFields[0], function ($subquery) use ($textSearch, $arrFields) {
                            $subquery->where($arrFields[0] . '.' . $arrFields[1], 'like', '\'%' . $textSearch . '%\'');
                        });
                    }
                }
            });
        }
        $query = $this->queryOperator($query);
        $query = $this->queryOrder($query);
        return  $query->paginate($this->pageSize, ['*'], $this->getPageName(), $this->getPage($this->getPageName()));
    }
    public function render()
    {
        return view($this->getView(), [
            'title' => $this->getTitle(),
            'buttons' => $this->getButtons(),
            'searchlayout' => $this->searchlayout,
            'showSearchlayout' => $this->showSearchUI(),
            'datatable' => $this->getData(),
            'tablecolumns' => $this->tablecolumns,
            'pageSizes' => $this->getPageSize(),
            'tableActions' => $this->tableActions ?? [],
            'searchWithColumns' => $this->getInputUI(),
        ]);
    }
}
