<?php

namespace Sokeio\UI\Table;

use Sokeio\UI\BaseUI;

trait TableRender
{
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
            $classNameRow = '';
            if ($this->classNameRow) {
                $classNameRow = call_user_func($this->classNameRow, $row, $this, $key);
            }
            if ($classNameRow) {
                $classNameRow = ' class="' . $classNameRow . '"';
            }
            $html .= <<<html
            <tr {$classNameRow} wire:key="sokeio-row-{$row->id}" row-index="{$row->id}">
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

        if ($data && method_exists($data, 'links')) {
            $keyModel = $this->getKeyWithTable('page.size');
            $current = $data->currentPage();
            $total = $data->total();

            $pageSize = $this->getValueByName('page.size', 10);
            $html .= '<div class="d-flex justify-content-center py-1">';
            $html .= '  <div class="m-0 text-secondary p-2">Showing <span>'
                . (($current - 1) * $data->perPage() + 1)
                . '</span> to <span>' . ($current * $data->perPage())
                . '</span> of <span>' . $total . '</span> entries</div>';

            $html .= '<div class=" ms-auto m-1">';
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
    public function formSearchRender()
    {
        if (!$this->hasChilds('formSearch')) {
            return '';
        }
        $formSearch = $this->renderChilds('formSearch', null, function (BaseUI $child) {
            $child->debounce();
            $child->prefix('soQuery.' . $this->getKeyWithTable());
        });
        $formSearchExtra = $this->renderChilds('formSearchExtra', null, function (BaseUI $child) {
            $child->debounce();
            $child->prefix('soQuery.' . $this->getKeyWithTable());
        });
        $htmlButton = <<<html
        <div class="sokeio-form-search-action">
            <button class="btn btn-primary btn-icon btn-sm" x-on:click="searchExtra = !searchExtra">
                <i class="ti ti-filter" x-show="!searchExtra" ></i>
                <i class="ti ti-filter-off" x-show="searchExtra" style="display: none" ></i>
            </button>
        </div>
        html;
        if (!$this->hasChilds('formSearchExtra')) {
            $htmlButton = '';
        }
        return <<<html
        <div class="sokeio-form-search-wrapper ">
            <div class="sokeio-form-search-main">
                {$formSearch}
                {$htmlButton}
            </div>
            <div class="sokeio-form-search-extra"  x-show="searchExtra" style="display: none">
                {$formSearchExtra}
            </div>
        </div>
        html;
    }
    public function view()
    {

        ksort($this->columns);
        $attr = $this->getAttr();
        $this->classWrapper('sokeio-table card');
        $attrWrapper = trim($this->getAttr('wrapper'));

        $orderBy = $this->getKeyWithTable('orderBy');
        $templateDataSelected = '';
        if ($this->showCheckBox) {
            $templateDataSelected = <<<HTML
            <template x-if="\$wire.dataSelecteds?.length>0">
                <div class="d-flex align-items-center p-2">
                    Selected:
                    <span x-text="\$wire.dataSelecteds.length"
                    class="fw-bold badge bg-primary text-bg-primary"
                    title="Selected items"></span>
                    <a class="btn btn-danger btn-sm ms-1" @click="\$wire.dataSelecteds = []">Clear</a>

                </div>
            </template>
            HTML;
        }
        return <<<HTML
        <div {$attrWrapper}  x-data="sokeioTable">
        {$this->formSearchRender()}
        {$templateDataSelected}
        <div class="table-responsive position-relative"
         x-init="tableInit"
          data-sokeio-table-order-by="{$orderBy}">
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
