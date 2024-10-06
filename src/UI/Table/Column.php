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
        return <<<html
        <th>
            <div class="d-flex align-items-center">
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
