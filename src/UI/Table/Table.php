<?php

namespace Sokeio\UI\Table;

use Sokeio\UI\BaseUI;
use Symfony\Component\Console\Messenger\RunCommandContext;

class Table extends BaseUI
{
    private $rows = null;
    private $query = null;
    private $pageIndex = null;
    private $pageSize = null;
    private $pageName = null;
    private $columns = [];
    private $context = null;
    private $index = 1;
    public function checkPage()
    {
        return $this->pageIndex && $this->pageSize;
    }
    protected function initUI()
    {
        $this->className('table table-bordered');
    }
    public function page($pageIndex = 1, $pageSize = 20, $pageName = 'page')
    {
        $this->pageIndex = $pageIndex;
        $this->pageSize = $pageSize;
        $this->pageName = $pageName;
        return $this;
    }
    public function context($context)
    {
        $this->context = $context;
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
        return $this->rows ?? [];
    }
    private function current()
    {
        return $this->columns[$this->index];
    }
    public function column($name, $callback = null)
    {
        $this->index++;
        $this->columns[$this->index] = (new Column($this))->setField($name)->setLabel($name);

        if ($callback) {
            $callback($this->current());
        }
        return $this;
    }
    public function enableIndex($callback = null)
    {
        $this->columns[0] = (new Column($this))
            ->setField('index')->setLabel('#')
            ->disableSort()
            ->renderCell(function ($row, $column, $index) use ($callback) {
                if ($callback) {
                    return $callback($row, $column, $index);
                }
                return $index + 1;
            });
        return $this;
    }
    private function headerRender()
    {
        $html = '';
        foreach ($this->columns as $column) {
            $html .= $column->getHeaderView();
        }
        return $html;
    }
    private function cellRender($row, $index = 0)
    {
        $html = '';
        foreach ($this->columns as $column) {
            $html .= $column->getCellView($row, $index);
        }
        return $html;
    }
    private function bodyRender()
    {
        $html = '';
        foreach ($this->getRows() as $key => $row) {
            $html .= <<<html
            <tr row-index="{$key}">
                {$this->cellRender($row,$key)}
            </tr>
            html;
        }
        return $html;
    }
    private function pagitateRender()
    {
        $data = $this->getRows();
        if (method_exists($data, 'links')) {
            return $data->links();
        }
        return  '';
    }
    public function view()
    {
        ksort($this->columns);
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
            {$this->pagitateRender()}
        </div>
        HTML;
    }
}
