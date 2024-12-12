<?php

namespace Sokeio\UI\Table;

use Sokeio\UI\BaseUI;
use Sokeio\UI\Common\Div;
use Sokeio\UI\Common\Tag;

class Column extends BaseUI
{
    public function enableLink(): static
    {
        return $this->vars('enableLink', true);
    }
    public function columnIndex($index): static
    {
        return $this->vars('columnIndex', $index);
    }
    public function getColumnIndex()
    {
        return $this->getVar('columnIndex', '', true);
    }
    private function fieldName($name): static
    {
        return $this->vars('name', $name);
    }
    public function attrHeader($key, $value): static
    {
        return $this->attr($key, $value, 'header');
    }
    public function label($label): static
    {
        return $this->vars('label', $label);
    }
    public function setTable(Table $table): static
    {
        return $this->vars('table', $table);
    }
    public static function make($fieldName = null)
    {
        return (new static(null))->fieldName($fieldName);
    }
    public function disableSort(): static
    {
        return $this->vars('disableSort', true);
    }
    public function classNameHeader($class): static
    {
        return $this->vars('classNameHeader', $class);
    }
    public function classNameCell($class): static
    {
        return $this->vars('classNameCell', $class);
    }
    public function editUI($ui, $when = null): static
    {
        return $this->child(Div::make($ui)->when($when)->className('edit-column')
            ->beforeRender(function (Div $div) {
                if($div->getUIIDkey()=='d633d791ff73bef6a5dca0ff5d38c09f'){
                    dd($div->getPrefix());
                }
                $prefix = $div->getParent()->getPrefix();
                $row = $div->getParams('row');
                $div->prefix($prefix . '.row_' . $row?->id . '.');
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
    public function getRowData()
    {
        return $this->getParams('row');
    }
    public function getValueCell()
    {
        $name = $this->getVar('name', null, true);
        if (!$name) {
            return '1';
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
}
