<?php

namespace Sokeio\Components\Concerns;

use function Termwind\render;

trait WithColumn
{
    public function disableEditInTable(): static
    {
        return $this->enableEditInTable(false);
    }
    public function enableEditInTable($flg = true): static
    {
        return $this->setKeyValue('enableEditInTable', $flg);
    }
    public function getEnableEditInTable()
    {
        return $this->getValue('enableEditInTable');
    }
    public function classLabel($classLabel): static
    {
        return $this->setKeyValue('classLabel', $classLabel);
    }
    public function getClassLabel()
    {
        return $this->getValue('classLabel');
    }

    public function noSort(): static
    {
        return $this->setKeyValue('noSort', true);
    }
    public function getNoSort()
    {
        return $this->getValue('noSort');
    }
    public function columnWidth($columnWidth): static
    {
        return $this->setKeyValue('columnWidth', $columnWidth);
    }
    public function getColumnWidth()
    {
        return $this->getValue('columnWidth');
    }
    public function cellClass($cellClass): static
    {
        return $this->setKeyValue('cellClass', $cellClass);
    }
    public function getCellClass()
    {
        return $this->getValue('cellClass');
    }
    public function withoutSearch(): static
    {
        return $this->setKeyValue('withoutSearch', true);
    }
    public function getWithoutSearch()
    {
        return $this->getValue('withoutSearch');
    }
    public function label($label): static
    {
        return $this->setKeyValue('label', $label);
    }
    public function getLabel()
    {
        return $this->getValue('label');
    }
    public function hideLabel(): static
    {
        return $this->setKeyValue('hideLabel', true);
    }
    public function getHideLabel()
    {
        return $this->getValue('hideLabel');
    }
    protected $fieldValueCallback = null;
    public function fieldValue($callback): static
    {
        $this->fieldValueCallback = $callback;
        return $this;
    }
    public function columnValueRender()
    {
        $row = $this->getDataItem();
        if ($this->fieldValueCallback) {
            return call_user_func($this->fieldValueCallback, $row, $this, $this->getManager());
        }
        if ($this->getEnableEditInTable() === true) {
            $this->hideLabel();
            return view($this->getView(), [
                'column' => $this
            ])->render();
        }
        return data_get($row, $this->getName());
    }
    public function setLink()
    {
        $this->fieldValue(function ($row) {
            echo '<a href="' . $row->getSeoCanonicalUrl() . '">' . data_get($row, $this->getName()) . '</a>';
        });
        return $this;
    }
}
