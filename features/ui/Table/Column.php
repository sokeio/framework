<?php

namespace Sokeio\UI\Table;

use Sokeio\UI\BaseUI;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Common\Tag;
use Sokeio\UI\Table\Concerns\ColumnData;

class Column extends BaseUI
{
    use ColumnData;
    private $paramCallback = null;
    private $groupColumn = null;
    private $rowData = null;
    private $rowIndex = null;
    private Table|null $table = null;
    public function columnGroup($groupColumn): static
    {
        return $this->tap(fn($c) => $c->groupColumn = $groupColumn);
    }
    public function getGroupColumn()
    {
        return $this->groupColumn;
    }
    public function checkEditInline($ui, $when = null)
    {
        if (!$this->getTable()->checkColumnEditable($this)) {
            return false;
        }
        if ($when) {
            return call_user_func($when, $ui);
        }
        return true;
    }
    public function getTable()
    {
        return $this->table;
    }
    public function setTable(Table $table): static
    {
        return $this->tap(function () use ($table) {
            $this->table = $table;
        });
    }
    public function setRowData($row, $index = null)
    {
        return $this->tap(function () use ($row, $index) {
            $this->rowData = $row;
            $this->rowIndex = $index;
        });
    }
    public function getRowData()
    {
        return $this->rowData;
    }
    public function columnParams($callback): static
    {
        return $this->tap(fn($c) => $c->paramCallback = $callback);
    }
    public function getColumnParams()
    {
        if ($this->paramCallback) {
            $rs = call_user_func($this->paramCallback, $this);
            if (is_array($rs)) {
                return $rs;
            }
        }
        return [];
    }
    public function width($width): static
    {
        if (!$width) {
            return $this;
        }
        if (is_numeric($width)) {
            $width .= 'px';
        }
        return $this->style('width', $width, 'header');
    }
    public function getColumnValueKey()
    {
        return data_get($this->getRowData(), $this->getTable()->getColumnKey());
    }
    public function editUI($ui, $when = null, $groupEditUI = 'tableColumnEdit'): static
    {
        return $this->child(Div::make($ui)
            ->attr('style', 'display: none')
            ->uiGroup($groupEditUI)
            ->when($when)
            ->attr('x-show', fn($divUI) => 'checkRowEditline(\'' . $divUI->getParams('rowValueId') . '\')')
            // ->when(fn($divUI) => $this->checkEditInline($divUI, $when))
            ->className('edit-column')
            ->beforeRender(function (Div $div) {
                $prefix = $div->getParent()->getPrefix();
                $dataId = $this->getColumnValueKey();
                $div->prefix($prefix . '.row_' . $dataId . '');
            })->afterRender(function (Div $div) {
                $div->prefix($div->getParent()->getPrefix());
            }), 'editUI');
    }
    public function cellUI($ui, $when = null, $withKey = null): static
    {
        return $this->child(
            Div::make($ui)
                ->childWithKey($withKey, null)
                ->attr('style', 'display: none')
                ->attr(
                    'x-show',
                    fn($divUI) =>
                    $this->hasChilds('editUI') ?
                        '!checkRowEditline(\'' . $divUI->getParams('rowValueId') . '\')'
                        : 'true'
                )
                // ->when(fn($divUI) => !$this->hasChilds('editUI') || !$this->checkEditInline($divUI))
                ->when($when),
            'cellUI'
        );
    }
    public function headerUI($ui, $when = null): static
    {
        return $this->child(Div::make($ui)
            ->when($when)
            ->className('sokeio-table-header-title')
            ->beforeRender(function (Div $div) {
                if (!$this->checkVar('disableSort', true)) {
                    $div->className('table-sort')
                        ->attr('x-bind:class', "{
                            'asc': typeSort === 'asc'&&fieldSort === '{$this->getFieldName()}',
                            'desc': typeSort === 'desc'&&fieldSort === '{$this->getFieldName()}'
                        }")
                        ->attr(
                            'x-on:click',
                            "sortField(\$el)"
                        )->attr('data-field', $this->getFieldName());
                }
            }), 'headerUI');
    }
    public function headerExtraUI($ui, $when = null): static
    {
        return $this->child(
            Div::make($ui)
                ->when($when)
                ->className('sokeio-table-header-extra'),
            'headerExtraUI'
        );
    }
    public function getFieldName()
    {
        return $this->getVar('name', '', true);
    }
    public function getValueCell()
    {
        $name = $this->getFieldName();
        if (!$name) {
            return '';
        }
        return data_get($this->getRowData(), $name);
    }
    protected function initUI()
    {
        parent::initUI();
        $this->register(function (self $base) {
            if (!$base->hasChilds('cellUI')) {
                $base->cellUI(Tag::make(function () use ($base) {
                    return $base->getValueCell();
                })->render(function ($ui) use ($base) {
                    if ($base->checkVar('enableLink')) {
                        $ui->a(function ($ui) {
                            return $ui->getParams('row')->url;
                        });
                    } else {
                        $ui->span();
                    }
                }));
            }
            if (!$base->hasChilds('headerUI')) {
                $base->headerUI(Div::make(function () use ($base) {
                    $label = $base->getVar('label', '',  true);
                    if ($label) {
                        return $label;
                    }
                    return $base->getVar('name', '', true);
                }));
            }
        });
    }
    public function viewHeader($rowSpan = null)
    {
        $attr = $this->getAttr('header');
        if ($rowSpan) {
            $attr .= ' rowspan="' . $rowSpan . '"';
        }
        return <<<HTML
        <th {$attr}>
            <div class="sokeio-table-header">
                {$this->renderChilds('headerUI')}
               {$this->renderChilds('headerExtraUI')}
            </div>
        </th>
        HTML;
    }
    public function view()
    {
        $this->attr('sokeio-table-row', $this->rowIndex);
        $attr = $this->getAttr();

        return <<<HTML
        <td {$attr}>
            <div class="sokeio-table-cell">
                {$this->renderChilds('cellUI')}
                {$this->renderChilds('editUI')}
            </div>
        </td>
        HTML;
    }
    public static function make($fieldName = null)
    {
        return (new static(null))->fieldName($fieldName)->label($fieldName);
    }
}
