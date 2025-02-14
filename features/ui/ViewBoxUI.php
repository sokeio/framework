<?php

namespace Sokeio\UI;

use Sokeio\UI\BaseUI;

class ViewBoxUI extends BaseUI
{
    private $query;
    private $pageSizes = [
        15,
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


    protected function initUI()
    {
        $this->className('table');
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
            $this->rows = $this->applyQuery()?->get();
        } else {
            $pageSize = $this->getValueByName('page.size', 15);
            $pageIndex = $this->getValueByName('page.index', 1);
            $this->rows = $this->applyQuery()?->paginate($pageSize, ['*'], $this->tableKey ?? 'page', $pageIndex);
        }
        return $this->rows ?? [];
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
        <div {$attrWrapper}>
        {$templateDataSelected}
        <div class="table-responsive position-relative" x-init="watchData" x-data="{
            fieldSort: '',
            typeSort: '',
            statusCheckAll: false,
            sortField(el) {
                let field = el.getAttribute('data-field');
                if(field != this.fieldSort) {
                    this.fieldSort = field;
                    this.typeSort = 'asc';
                } else {
                    this.typeSort = this.typeSort === 'asc' ? 'desc' : 'asc';
                    if(this.typeSort === 'asc') {
                        this.fieldSort = '';
                        this.typeSort = '';
                    }
                }
                    \$wire.callActionUI('{$orderBy}', {
                        'field': this.fieldSort,
                        'type': this.typeSort
                    });
            },
            watchData() {
            \$watch('\$wire.dataSelecteds', () => {
             let checkedValues = [...\$el.querySelectorAll('.sokeio-checkbox-one')]
                .map(el=>el.value);
                this.statusCheckAll = checkedValues.length === checkedValues.filter(el =>
                \$wire.dataSelecteds.includes(el)).length;
            });
            Livewire.hook('request', ({ component, commit, respond, succeed, fail }) => {
                succeed(({ snapshot, effect }) => {
                    setTimeout(() => {
                        let checkedValues = [...\$el.querySelectorAll('.sokeio-checkbox-one')]
                    .map(el=>el.value);
                    this.statusCheckAll = checkedValues.length === checkedValues.filter(el =>
                    \$wire.dataSelecteds.includes(el)).length;
                    }, 0);
                })
            })
            },
            checkboxAll(ev) {
                let isChecked = ev.target.checked;
                let checkedValues = [...this.\$el.closest('table').querySelectorAll('.sokeio-checkbox-one')]
                .map(el=>el.value);
                if(isChecked) {
                    \$wire.dataSelecteds = \$wire.dataSelecteds.concat(checkedValues);
                } else {
                     \$wire.dataSelecteds= \$wire.dataSelecteds.filter(el=> !checkedValues.includes(el));
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
        </div>
        {$this->pagitateRender()}
        </div>
        HTML;
    }
}
