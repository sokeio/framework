<?php

namespace Sokeio\UI\Table;

use Sokeio\UI\BaseUI;
use Sokeio\UI\Field\Checkbox;
use Sokeio\UI\Field\Input;
use Sokeio\UI\Table\Concerns\TableRender;
use Sokeio\UI\Table\Concerns\WithDatasource;

class Table extends BaseUI
{
    use TableRender, WithDatasource;
    private $columns = [];
    private $columnGroups = [];
    private $columnKey = 'id';
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
    public function columnGroup($key, $label = null)
    {
        return $this->tap(fn($c) => $c->columnGroups[$key] = $label);
    }

    public function checkColumnEditable(Column $column)
    {
        if (!in_array($column->getColumnValueKey(), $this->getWire()->tableRowEditline)) {
            return false;
        }
        return true;
    }
    public function columnKey($key)
    {
        $this->columnKey = $key ?? 'id';
        return $this;
    }
    public function getColumnKey()
    {
        return $this->columnKey;
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
    private function getKeyWithTable($name = '')
    {
        if ($name) {
            $name = '.' . $name;
        }

        return 'tb_' . $this->getUIIDkey() . $name;
    }
    protected function getValueByName($name, $default = null, $withKey = true)
    {
        if (!$this->getContext()) {
            return $this->getWire()->getValueQuery($this->getKeyWithTable($name, $withKey), $default);
        }
        return data_get($this->getContext(), $this->getKeyWithTable($name, $withKey), $default);
    }
    protected function setValueByName($name, $value, $withKey = true)
    {
        $this->getWire()->query($this->getKeyWithTable($name, $withKey), $value);
        if ($this->getContext()) {
            data_set($this->getContext(), $this->getKeyWithTable($name, $withKey), $value);
        }
        return $this;
    }
    protected function initUI()
    {
        $this->className('table sokeio-table');
        $this->action('paginate', function ($page) {
            $this->setValueByName('page.index', $page);
        }, false);
        $keyFnOrderBy = $this->getKeyWithTable('order-by');
        $this->attr('data-sokeio-table-order-by', $keyFnOrderBy);
        $this->action($keyFnOrderBy, function ($order) {
            $this->setValueByName('order.field', $order['field'] ?? '');
            $this->setValueByName('order.type',  $order['type'] ?? 'asc');
        }, false);
        $this->register(function (self $table) {
            if (!$table->getPrefix()) {
                $table->prefix('tableEditline');
            }
        });
    }

    public function showAll()
    {
        return $this->vars('showAll', true);
    }
    public function column($nameOrColumn, $callback = null, $label = null, $index = null)
    {
        if ($index === null) {
            $this->index++;
            $columnIndex = $this->index;
        } else {
            $columnIndex = $index;
        }

        if (!($nameOrColumn instanceof Column)) {
            $nameOrColumn = Column::make($nameOrColumn);
        }
        $nameOrColumn->setTable($this)->tap($callback)->columnIndex($columnIndex);
        if ($label) {
            $nameOrColumn->label($label);
        }
        if (!$label) {
            $label = $nameOrColumn->getLabel();
        }

        $this->columns[$columnIndex] = $nameOrColumn;
        $this->child($nameOrColumn, 'column_' . $columnIndex);

        return $this; // $this->setupChild(fn($c) => $label && $c->label($label));
    }
    public function columnAction($array, $title = 'Actions', $callback = null, $with = 200)
    {
        return $this->column(
            Column::make()
                ->cellUI($array)
                ->label($title)
                ->disableSort()
                ->width($with)
                ->tap($callback),
            null,
            null,
            999999999999999
        );
    }
    public function enableIndex($callback = null)
    {
        return $this->column(
            Column::make()
                ->label('#')
                ->cellUI(function ($ui) {
                    $datasource = $ui->getParams('datasource');
                    $index = $ui->getParams('index');
                    if (method_exists($datasource, 'firstItem')) {
                        return  $datasource->firstItem() + $index + 1;
                    }
                    return $index + 1;
                })
                ->disableSort()
                ->tap($callback),
            null,
            null,
            -1
        );
    }
    public function enableCheckBox($callback = null, $isAfterIndex = true, $withoutPrefix = true)
    {
        return $this->column(
            Column::make()
                ->width(20)
                ->headerUI(
                    Checkbox::make()
                        ->styleWrapper('text-align', 'center')
                        ->className('sokeio-checkbox-all')
                        ->skipFill()
                        ->attr('@change', 'checkboxAll')
                        ->attr('x-model', 'statusCheckAll')
                )
                ->cellUI(Checkbox::make('dataSelecteds')
                    ->skipFill()
                    ->className('sokeio-checkbox-one')
                    ->styleWrapper('text-align', 'center')
                    ->tap(function (Checkbox $checkbox) use ($withoutPrefix) {
                        if ($withoutPrefix) {
                            $checkbox->beforeRender(function (Checkbox $checkbox) {
                                $checkbox->prefix('');
                            });
                        }
                    })
                    ->valueDefault([])
                    ->wireKey(function (Checkbox $checkbox) {
                        $row = $checkbox->getParams('row');
                        $index = $checkbox->getParams('index');
                        return "sokeio-checkbox-" . $index . "-" . $row->id;
                    })
                    ->attr('value', function (Checkbox $checkbox) {
                        $row = $checkbox->getParams('row');
                        return $row->id;
                    }))
                ->disableSort()
                ->tap($callback),
            null,
            null,
            $isAfterIndex ? -2 : 0
        )->vars('enableCheckBox', true);
    }

    public function formSearch($fields, $fieldExtra = null): self
    {
        return $this->child($fields, 'formSearch')
            ->child($fieldExtra, 'formSearchExtra');
    }
    public function searchbox($columns = ['name'], $placeholder = 'Search'): self
    {
        return $this->child([
            Input::make('keyword')
                ->placeholder($placeholder)
                ->withQuery(function ($query, $value) use ($columns) {
                    $query->whereAny($columns, 'like', '%' . $value . '%');
                }),
        ], 'formSearch');
    }
}
