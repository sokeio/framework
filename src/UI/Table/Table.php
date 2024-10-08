<?php

namespace Sokeio\UI\Table;

use Sokeio\UI\BaseUI;


class Table extends BaseUI
{
    private $rows = [];
    private $query = null;
    private $pageIndex = null;
    private $pageSize = null;
    private $pageName = null;
    private $colums = [];
    private $index = -1;
    public function checkPage(){
        return $this->pageIndex && $this->pageSize;
    }
    protected function initUI()
    {
        $this->className('table table-bordered');
    }
    public function page($pageIndex, $pageSize = 20, $pageName = 'page')
    {
        $this->pageIndex = $pageIndex;
        $this->pageSize = $pageSize;
        $this->pageName = $pageName;
        return $this;
    }
    public function query($query)
    {
        $this->query = $query;
        return $this;
    }
    public function getRows()
    {
        if ($this->checkPage()) {
            $this->rows = $this->query->paginate($this->pageSize, ['*'], $this->pageName ?? 'page', $this->pageIndex);
        } else {
            $this->rows = $this->query->get();
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
        <div class="table-responsive" x-data="{
            fieldSort: '',
            typeSort: '',
            sortField(el) {
                let field = el.getAttribute('data-field');
                if(field != this.fieldSort) {
                    this.fieldSort = field;
                    this.typeSort = 'asc';
                } else {
                    this.typeSort = this.typeSort === 'asc' ? 'desc' : 'asc';
                }
            }
        }">
            <table {$attr} >
                <thead>
                    {$this->headerRender()}
                </thead>
                <tbody>
                    {$this->bodyRender()}
                </tbody>
            </table>
        </div>
        HTML;
    }
}
