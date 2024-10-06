<?php

namespace Sokeio\UI\Table;

use Sokeio\UI\BaseUI;


class Table extends BaseUI
{
    private $rows = [];
    private $query = null;
    private $colums = [];
    private $index = -1;
    public function query($query)
    {
        $this->query = $query;
        return $this;
    }
    public function getRows()
    {
        if ($this->query) {
            $this->rows = $this->query->get();
            // dd($this->rows);
        }
        return $this->rows;
    }
    private function current()
    {
        return $this->colums[$this->index];
    }
    public function column($name, $callback = null)
    {
        $this->index++;
        $this->colums[$this->index] = (new Column($this))->setField($name)->setLabel($name);

        if ($callback) {
            $callback($this->current());
        }
        return $this;
    }
    private function headerRender()
    {
        $html = '';
        foreach ($this->colums as $column) {
            $html .= $column->getHeaderView();
        }
        return $html;
    }
    private function cellRender($row)
    {
        $html = '';
        foreach ($this->colums as $column) {
            $html .= $column->getCellView($row);
        }
        return $html;
    }
    private function bodyRender()
    {
        $html = '';
        foreach ($this->getRows() as $row) {
            $html .= <<<html
            <tr>
                {$this->cellRender($row)}
            </tr>
            html;
        }
        return $html;
    }
    public function view()
    {
        $attr = $this->getAttr();
        return <<<HTML
        <table {$attr}>
            <thead>
                {$this->headerRender()}
            </thead>
            <tbody>
                {$this->bodyRender()}
            </tbody>
        </table>
        HTML;
    }
}
