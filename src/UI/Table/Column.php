<?php

namespace Sokeio\UI\Table;

class Column
{
    use ColumnData;

    public function __construct(
        protected Table $table
    ) {}
    public function getValue($row)
    {
        return data_get($row, $this->getField());
    }
    public function getHeaderView()
    {
        $name = $this->getField();
        return <<<html
        <th>
            <div class="d-flex align-items-center table-sort"
            x-bind:class="{'asc': typeSort === 'asc'&&fieldSort === '{$name}', 'desc': typeSort === 'desc'&&fieldSort === '{$name}'}"
            data-field="{$name}" x-on:click="sortField(\$el)">
               {$this->getLabel()}
            </div>
        </th>
        html;
    }

    public function getCellView($row)
    {
        return <<<html
        <td>
            <div class="d-flex align-items-center">
             {$this->getValue($row)}
            </div>
        </td>
        html;
    }
}
