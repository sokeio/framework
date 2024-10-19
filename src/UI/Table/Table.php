<?php

namespace Sokeio\UI\Table;

use Sokeio\Theme;
use Sokeio\UI\BaseUI;

class Table extends BaseUI
{
    private $tableKey = '';
    private $rows = null;
    private $query = null;
    private $showAll = false;
    private $columns = [];
    private $context = null;
    private $index = 1;
    protected function getValueByName($name, $default = null)
    {
        return data_get($this->context, $name, $default);
    }
    protected function setValueByName($name, $value)
    {
        data_set($this->context, $name, $value);
        $this->getWire()->data($name, $value);
        return $this;
    }
    protected function initUI()
    {
        $this->className('table table-bordered');
        $this->action('paginate', function ($page) {
            $this->setValueByName('page.index', $page);
        });
    }
    public function context(&$context)
    {
        $this->context = $context;
        return $this;
    }

    public function showAll()
    {
        $this->showAll = true;
        return $this;
    }
    public function key($key)
    {
        $this->tableKey = $key;
        return $this;
    }
    public function query($query)
    {
        $this->query = $query;
        return $this;
    }
    public function getRows()
    {
        if ($this->showAll) {
            $this->rows = $this->query->get();
        } else {
            $pageSize = $this->getValueByName('page.size', 10);
            $pageIndex = $this->getValueByName('page.index', 1);
            $this->rows = $this->query->paginate($pageSize, ['*'], $this->tableKey ?? 'page', $pageIndex);
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
                return $this->getRows()->firstItem() + $index;
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
        $html = '';
        $data = $this->getRows();
        if (method_exists($data, 'links')) {
            $current = $data->currentPage();
            $total = $data->total();
            $html .= '<div class="d-flex justify-content-center py-3">';
            $html .= '  <p class="m-0 text-secondary">Showing <span>' . (($current - 1) * $data->perPage() + 1) . '</span> to <span>' . ($current * $data->perPage()) . '</span> of <span>' . $total . '</span> entries</p>';

            // <li class="page-item"><a class="page-link" href="#">1</a></li>
            $startPage = $current - 2;
            $endPage = $current + 2;
            if ($startPage < 1) {
                $startPage = 1;
            }
            if ($startPage === 1) {
                $endPage = 5;
            }
            if ($current === $data->lastPage()) {
                $startPage = $data->lastPage() - 4;
            }
            if ($startPage < 1) {
                $startPage = 1;
            }
            if ($endPage > $data->lastPage()) {
                $endPage = $data->lastPage();
            }
            $nextPage = $current + 1;
            if ($nextPage > $endPage) {
                $nextPage = $endPage;
            }
            $backPage = $current - 1;
            if ($backPage < 1) {
                $backPage = 1;
            }
            $html .= <<<html
            <ul class="pagination m-0 ms-auto">
            
       <li class="page-item me-1" wire:click="callActionUI('paginate','{$backPage}')">
           <a class="page-link  bg-azure text-white" >
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                   stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                   class="icon">
                   <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                   <path d="M15 6l-6 6l6 6"></path>
               </svg>
           </a>
       </li>
       html;

            for ($i = $startPage; $i <= $endPage; $i++) {
                if ($i === $current) {
                    $html .= <<<html
                            <li class="page-item active" wire:click="callActionUI('paginate','{$i}')">
                                <a class="page-link" >
                                    {$i}
                                </a>
                            </li>
                            html;
                    continue;
                }
                $html .= <<<html
         <li class="page-item" wire:click="callActionUI('paginate','{$i}')">
            <a class="page-link" >
                {$i}
            </a>
        </li>
        html;
            }
            $html .= <<<html
         <li class="page-item ms-1" wire:click="callActionUI('paginate','{$nextPage}')">
            <a class="page-link bg-azure text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M9 6l6 6l-6 6"></path>
                </svg>
            </a>
        </li>
    </ul>
    html;
            $html .= '</div>';
        }
        return   $html;
    }
    public function view()
    {
        ksort($this->columns);
        $attr = $this->getAttr();
        return <<<HTML
        <div class="table-responsive position-relative" x-data="{
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
        <div wire:loading class="position-absolute top-50 start-50 translate-middle">
        <span  class="spinner-border  text-blue  " role="status"></span>
        </div>
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
