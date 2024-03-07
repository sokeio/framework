<?php

namespace Sokeio\Components\Field\Concerns;

use Sokeio\Components\Common\Concerns\WithTitle;
trait WithCheckbox
{
    use WithTitle;
    public function checkboxValue($checkboxValue): static
    {
        return $this->setKeyValue('checkboxValue', $checkboxValue);
    }
    public function getCheckboxValue()
    {
        return $this->getValue('checkboxValue', 1);
    }
    public function getWireAttribute()
    {
        $attr = parent::getWireAttribute();

        if (data_get($this->getManager(), $this->getFormField())) {
            $attr .= ' checked ';
        }
        $attr .= ' value="' . $this->getCheckboxValue() . '" ';
        return $attr;
    }
}

