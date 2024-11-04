<?php

namespace Sokeio\UI\Table;

use Sokeio\UI\BaseUI;

class Table extends BaseUI
{
    use TableRender;
    private $tableKey = '';
    private $rows = null;
    private $query = null;
    private $showAll = false;
    private $showCheckBox = false;
    private $columns = [];
    private $classNameRow = null;
    private $index = 1;
    private $pageSizes = [
        15,
        25,
        50,
        100,
        200,
        500,
        1000,
        2000
    ];
    public function classNameRow($callback)
    {
        if ($callback) {
            $this->classNameRow = $callback;
        }
        return $this;
    }

    public function attrWrapper($name, $value)
    {
        return $this->attr($name, $value, 'wrapper');
    }
    public function classWrapper($class)
    {
        return $this->attrWrapper('class', $class);
    }
    public function pageSizes($sizes)
    {
        if (!is_array($sizes)) {
            $sizes = [$sizes];
        }
        $this->pageSizes = $sizes;
        return $this;
    }
    private function getKeyWithTable($name, $withKey = true)
    {
        if ($this->tableKey && $withKey) {
            return 'table.' . $this->tableKey . '.' . $name;
        }
        return 'table.' . $name;
    }
    protected function getValueByName($name, $default = null, $withKey = true)
    {
        if (!$this->context) {
            return $this->getWire()->getValueQuery($this->getKeyWithTable($name, $withKey), $default);
        }
        return data_get($this->context, $this->getKeyWithTable($name, $withKey), $default);
    }
    protected function setValueByName($name, $value, $withKey = true)
    {
        $this->getWire()->query($this->getKeyWithTable($name, $withKey), $value);
        if ($this->context) {
            data_set($this->context, $this->getKeyWithTable($name, $withKey), $value);
        }
        return $this;
    }
    protected function initUI()
    {
        $this->className('table');
        $this->action('paginate', function ($page) {
            $this->setValueByName('page.index', $page);
        });
        $this->action($this->getKeyWithTable('orderBy'), function ($order) {
            $field = $order['field'];
            $type = $order['type'] ?? 'asc';
            $this->setValueByName('order.field', $field);
            $this->setValueByName('order.type', $type);
        });
    }

    public function showAll()
    {
        $this->showAll = true;
        return $this;
    }
    public function tableKey($key)
    {
        $this->tableKey = $key;
        return $this;
    }
    public function query($query)
    {
        $this->query = $query;
        return $this;
    }
    public function applyQuery()
    {
        $query = $this->query;
        $orderBy = $this->getValueByName('order.field');
        $type = $this->getValueByName('order.type', 'asc');
        if ($orderBy) {
            $query = $query->orderBy($orderBy, $type);
        }
        return $query;
    }
    public function getRows()
    {
        if ($this->showAll) {
            $this->rows = $this->applyQuery()?->get();
        } else {
            $pageSize = $this->getValueByName('page.size', 15);
            $pageIndex = $this->getValueByName('page.index', 1);
            $this->rows = $this->applyQuery()?->paginate($pageSize, ['*'], $this->tableKey ?? 'page', $pageIndex);
        }
        return $this->rows ?? [];
    }
    private function current()
    {
        return $this->columns[$this->index];
    }
    public function column($nameOrColumn, $callback = null)
    {
        $this->index++;
        if ($nameOrColumn instanceof Column) {
            $nameOrColumn->setTable($this);
            $this->columns[$this->index] = $nameOrColumn;
        } else {
            $this->columns[$this->index] = Column::init()
                ->setTable($this)
                ->setField($nameOrColumn)
                ->setLabel($nameOrColumn);
        }
        if ($callback) {
            $callback($this->current());
        }
        return $this;
    }
    public function columnAction($array, $title = 'Actions', $callback = null)
    {
        return $this->child($array, 'columnAction')->boot(function () use ($title, $callback) {
            $actionColumn = (new Column($this))
                ->setLabel($title)
                ->classNameHeader('w-1')
                ->disableSort()
                ->renderCell(function ($row, $column, $index) {
                    return $this->renderChilds('columnAction', [
                        'row' => $row,
                        'column' => $column,
                        'index' => $index
                    ]);
                });

            if ($callback) {
                $callback($actionColumn);
            }
            $this->columns[++$this->index] = $actionColumn;
        });
    }
    public function enableIndex($callback = null)
    {
        return $this->boot(function () use ($callback) {
            $this->columns[-1] = (new Column($this))
                ->setField('index')->setLabel('#')
                ->disableSort()
                ->classNameHeader('w-1')
                ->renderCell(function ($row, $column, $index) {
                    return $this->getRows()->firstItem() + $index;
                });

            if ($callback) {
                $callback($this->columns[-1]);
            }
        });
    }
    public function enableCheckBox($callback = null, $isAfterIndex = true)
    {
        return $this->boot(function () use ($callback, $isAfterIndex) {
            $this->showCheckBox = true;
            $this->columns[$isAfterIndex ? -2 : 0] = (new Column($this))
                ->setField('index')->setLabel('#')
                ->disableSort()
                ->classNameHeader('w-1')
                ->renderHeader(function () {
                    return '<input type="checkbox" @change="checkboxAll" x-model="statusCheckAll" name="select-all"
                 class="form-check-input sokeio-checkbox-all">';
                })
                ->renderCell(function ($row, $column, $index) {
                    return '<input type="checkbox" wire:key="sokeio-checkbox-' . $index . '-' . $row->id . '"
                 name="selected[]" class="form-check-input sokeio-checkbox-one"
                 wire:model="dataSelecteds" value="' . $row->id . '">';
                });
            if ($callback) {
                $callback($this->columns[$isAfterIndex ? -2 : 0]);
            }
        });
    }
}
