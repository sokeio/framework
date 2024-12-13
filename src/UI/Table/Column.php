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
    private $rowData = null;
    private $rowIndex = null;
    private Table|null $table = null;
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
        $this->paramCallback = $callback;
        return $this;
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
    public function editUI($ui, $when = null): static
    {
        return $this->child(Div::make($ui)->when($when)->className('edit-column')
            ->beforeRender(function (Div $div) {
                $prefix = $div->getParent()->getPrefix();
                $row = $div->getParams('row');
                $div->prefix($prefix . '.row_' . $row?->id . '');
            })->afterRender(function (Div $div) {
                $div->prefix($div->getParent()->getPrefix());
            }), 'editUI');
    }
    public function cellUI($ui, $when = null): static
    {
        return $this->child(Div::make($ui)->when($when), 'cellUI');
    }
    public function headerUI($ui, $when = null): static
    {
        return $this->child(Div::make($ui)->when($when), 'headerUI');
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
    public function viewHeader()
    {
        $attr = $this->getAttr('header');
        return <<<HTML
        <th {$attr}>
            <div class="sokeio-table-header">
                {$this->renderChilds('headerUI')}
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
        return (new static(null))->fieldName($fieldName);
    }
}
