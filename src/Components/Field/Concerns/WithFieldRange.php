<?php

namespace Sokeio\Components\Field\Concerns;

use Sokeio\Components\UI;

trait WithFieldRange
{
    public function fromField($fromField): static
    {
        return $this->setKeyValue('fromField', $fromField);
    }
    public function getFromField()
    {
        if ($this->checkPrex() && ($value = $this->getValue('fromField'))) {
            return $this->getPrex() . '.' .  str_replace('.', UI::KEY_FIELD_NAME, $value);
        }
        return $this->getValue('fromField');
    }
    public function toField($toField): static
    {
        return $this->setKeyValue('toField', $toField);
    }
    public function getToField()
    {
        if ($this->checkPrex() && ($value = $this->getValue('toField'))) {
            return $this->getPrex() . '.' .  str_replace('.', UI::KEY_FIELD_NAME, $value);
        }
        return $this->getValue('toField');
    }
    public function maxValue($maxValue): static
    {
        return $this->setKeyValue('maxValue', $maxValue);
    }
    public function getMaxValue()
    {
        return $this->getValue('maxValue');
    }
    public function minValue($minValue): static
    {
        return $this->setKeyValue('minValue', $minValue);
    }
    public function getMinValue()
    {
        return $this->getValue('minValue');
    }
}
