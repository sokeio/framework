<?php

namespace Sokeio\Components\Concerns;

trait WithColumn
{

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
    protected $fieldValueCallback = null;
    public function fieldValue($callback): static
    {
        $this->fieldValueCallback = $callback;
        return $this;
    }
    public function getFieldValue($row)
    {
        if ($this->fieldValueCallback) {
            return call_user_func($this->fieldValueCallback, $row, $this, $this->getManager());
        }
        return data_get($row, $this->getName());
    }
}
