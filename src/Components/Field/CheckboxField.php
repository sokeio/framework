<?php

namespace Sokeio\Components\Field;


class CheckboxField extends BaseField
{
    public function getWireAttribute()
    {
        $attr = parent::getWireAttribute();

        if (data_get($this->getManager(), $this->getFormField())) {
            $attr .= ' checked ';
        }
        $attr .= ' value="' . $this->getCheckboxValue() . '" ';
        return $attr;
    }
    public function Title($Title): static
    {
        return $this->setKeyValue('Title', $Title);
    }
    public function getTitle()
    {
        return $this->getValue('Title');
    }
    public function CheckboxValue($CheckboxValue): static
    {
        return $this->setKeyValue('CheckboxValue', $CheckboxValue);
    }
    public function getCheckboxValue()
    {
        return $this->getValue('CheckboxValue', 1);
    }
    public function getFieldView()
    {
        return 'sokeio::components.field.checkbox';
    }
}
