<?php

namespace Sokeio\Admin\Components\Concerns;

trait WithColumn
{

    public function ClassLabel($ClassLabel): static
    {
        return $this->setKeyValue('ClassLabel', $ClassLabel);
    }
    public function getClassLabel()
    {
        return $this->getValue('ClassLabel');
    }

    public function NoSort(): static
    {
        return $this->setKeyValue('NoSort', true);
    }
    public function getNoSort()
    {
        return $this->getValue('NoSort');
    }
    public function Label($Label): static
    {
        return $this->setKeyValue('Label', $Label);
    }
    public function getLabel()
    {
        return $this->getValue('Label');
    }
    protected $fieldValueCallback = null;
    public function FieldValue($callback): static
    {
        $this->fieldValueCallback = $callback;
        return $this;
    }
    public function getFieldValue($row)
    {
        if ($this->fieldValueCallback) return call_user_func($this->fieldValueCallback, $row, $this, $this->getManager());
        return data_get($row, $this->getName());
    }
}
