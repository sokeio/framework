<?php

namespace Sokeio\UI\Table;

use Sokeio\UI\BaseUI;
use Sokeio\UI\Common\Div;

class Column extends BaseUI
{
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
        return $this->child(Div::make($ui)->when($when)->className('edit-column'), 'editUI');
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
                $base->cellUI(Div::make(function () use ($base) {
                    return $base->getValueCell();
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
        <th {$attr}> {$this->renderChilds('headerUI')}</th>
        HTML;
    }
    public function view()
    {
        $attr = $this->getAttr();
        return <<<HTML
        <td {$attr}>{$this->renderChilds('cellUI')}{$this->renderChilds('editUI')}</td>
        HTML;
    }
}
