<?php

namespace Sokeio\Admin\Components\Field\Concerns;

use Sokeio\Admin\Components\UI;

trait WithFieldRange
{
    public function FromField($FromField): static
    {
        return $this->setKeyValue('FromField', $FromField);
    }
    public function getFromField()
    {
        if ($this->checkPrex() && ($value = $this->getValue('FromField'))) {
            return $this->getPrex() . '.' .  str_replace('.', UI::KEY_FIELD_NAME, $value);
        }
        return $this->getValue('FromField');
    }
    public function ToField($ToField): static
    {
        return $this->setKeyValue('ToField', $ToField);
    }
    public function getToField()
    {
        if ($this->checkPrex() && ($value = $this->getValue('ToField'))) {
            return $this->getPrex() . '.' .  str_replace('.', UI::KEY_FIELD_NAME, $value);
        }
        return $this->getValue('ToField');
    }
    public function MaxValue($MaxValue): static
    {
        return $this->setKeyValue('MaxValue', $MaxValue);
    }
    public function getMaxValue()
    {
        return $this->getValue('MaxValue');
    }
    public function MinValue($MinValue): static
    {
        return $this->setKeyValue('MinValue', $MinValue);
    }
    public function getMinValue()
    {
        return $this->getValue('MinValue');
    }
}
