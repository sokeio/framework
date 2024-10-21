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
    private $pageSizes = [
        10,
        25,
        50,
        100,
        200,
        500,
        1000,
        2000
    ];
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
    public function columnAction($array)
    {
        return $this->child($array, 'columnAction');
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
            $this->rows = $this->applyQuery()->get();
        } else {
            $pageSize = $this->getValueByName('page.size', 10);
            $pageIndex = $this->getValueByName('page.index', 1);
            $this->rows = $this->applyQuery()->paginate($pageSize, ['*'], $this->tableKey ?? 'page', $pageIndex);
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
            $keyModel = $this->getKeyWithTable('page.size');
            $current = $data->currentPage();
            $total = $data->total();

            $pageSize = $this->getValueByName('page.size', 10);
            $html .= '<div class="d-flex justify-content-center py-3">';
            $html .= '  <p class="m-0 text-secondary">Showing <span>' . (($current - 1) * $data->perPage() + 1)
                . '</span> to <span>' . ($current * $data->perPage())
                . '</span> of <span>' . $total . '</span> entries</p>';

            $html .= '<div class="ms-auto m-1">';
            $html .= '    <select class="form-select p-1 px-3" wire:model.live="soQuery.' . $keyModel . '">';
            foreach ($this->pageSizes as $item) {
                $att = ($item == $pageSize ? 'selected' : '');
                $html .= '<option value="' . $item . '" ' . $att . '>' . $item . '</option>';
            }
            $html .= '   </select>';
            $html .= ' </div>';
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
            <ul class="pagination m-1 ">
            
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
        $this->columns[++$this->index] = (new Column($this))
            ->setLabel('Actions')
            ->disableSort()
            ->renderCell(function ($row, $column, $index) {
                return $this->renderChilds('columnAction', [
                    'row' => $row,
                    'column' => $column,
                    'index' => $index
                ]);
            });
        ksort($this->columns);
        $attr = $this->getAttr();
        $attrWrapper = trim($this->getAttr('wrapper'));
        if ($attrWrapper == '') {
            $attrWrapper = 'class="mb-3 card p-2"';
        }
        $orderBy = $this->getKeyWithTable('orderBy');
        return <<<HTML
        <div {$attrWrapper}>
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
                    \$wire.callActionUI('{$orderBy}', {
                        'field': this.fieldSort,
                        'type': this.typeSort
                    });
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
        </div>
        {$this->pagitateRender()}
        </div>
        HTML;
    }
}
