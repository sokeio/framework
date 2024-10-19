<?php

namespace Sokeio\UI\Table;

class Column
{
    use ColumnData;

    public function __construct(
        protected Table $table
    ) {}
    public function getTable()
    {
        return $this->table;
    }
    public function getValue($row, $index = 0)
    {
        $callback = $this->getRenderCell();
        if ($callback && is_callable($callback)) {
            return call_user_func($callback, $row, $this, $index);
        }
        return data_get($row, $this->getField());
    }
    public function getHeaderView()
    {
        $name = $this->getField();
        if ($this->getDisableSort()) {
            return <<<html
            <th>
                <div class="d-flex align-items-center" data-field="{$name}">
                   {$this->getLabel()}
                </div>
            </th>
            html;
        }
        return <<<html
        <th>
            <div class="d-flex align-items-center table-sort"
            x-bind:class="{
                'asc': typeSort === 'asc'&&fieldSort === '{$name}',
                'desc': typeSort === 'desc'&&fieldSort === '{$name}'
            }"
            data-field="{$name}" x-on:click="sortField(\$el)">
               {$this->getLabel()}
            </div>
        </th>
        html;
    }

    public function getCellView($row, $index = 0)
    {
        return <<<html
        <td>
            <div class="d-flex align-items-center">
             {$this->getValue($row,$index)}
            </div>
        </td>
        html;
    }
}
