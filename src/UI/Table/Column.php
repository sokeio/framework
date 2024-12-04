<?php

namespace Sokeio\UI\Table;


class Column
{
    use ColumnData;
    public static function init($field, $label = null)
    {
        return new static($field, $label);
    }
    public function __construct($field, $label = null)
    {
        $this->setField($field);
        if (!$label) {
            $label = $field;
        }
        $this->setLabel($label);
    }
    protected $table;

    public function setTable(Table $table)
    {
        $this->table = $table;
        return $this;
    }
    public function getTable()
    {
        return $this->table;
    }
    public function classNameHeader($class)
    {
        return $this->setData('classNameHeader', $class);
    }
    public function getValue($row, $index = 0)
    {
        $callback = $this->getRenderCell();
        if ($callback && is_callable($callback)) {
            return call_user_func($callback, $row, $this, $index);
        }
        return data_get($row, $this->getField());
    }
    protected function getHeaderContent()
    {
        $callback = $this->getRenderHeader();
        if ($callback && is_callable($callback)) {
            return call_user_func($callback, $this);
        }
        return $this->getLabel();
    }
    public function getHeaderView()
    {
        $name = $this->getField();
        $class = $this->getData('classNameHeader');
        if ($this->getDisableSort()) {
            return <<<html
            <th class="{$class}">
                <div class="d-flex align-items-center cell-header" data-field="{$name}">
                   {$this->getHeaderContent()}
                </div>
            </th>
            html;
        }
        return <<<html
        <th class="{$class}">
            <div class="d-flex align-items-center cell-header" data-field="{$name}"  x-on:click="sortField(\$el)">
                <div class="table-sort"
                x-bind:class="{
                    'asc': typeSort === 'asc'&&fieldSort === '{$name}',
                    'desc': typeSort === 'desc'&&fieldSort === '{$name}'
                }">
                {$this->getHeaderContent()}
                </div>
            </div>
        </th>
        html;
    }

    public function getCellView($row, $index = 0)
    {
        $classCell = $this->getClassNameCell() ?? '';
        if ($classCell && is_callable($classCell)) {
            $classCell = call_user_func($classCell, $row, $this, $index);
        }
        if ($classCell) {
            $classCell = ' class="' . $classCell . '"';
        }
        return <<<html
        <td {$classCell}>
            <div class="d-flex align-items-center cell-value "
            data-row-field="{$this->getField()}"
            data-row-index="{$index}"
            data-row-id="{$row->id}">
             {$this->getValue($row,$index)}
            </div>
        </td>
        html;
    }
}
