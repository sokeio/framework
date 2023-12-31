<?php

namespace Sokeio\Components\Field;

use Sokeio\Components\Field\Concerns\WithFieldRange;

class NumberField extends BaseField
{
    use WithFieldRange;
    public function Digits($Digits): static
    {
        return $this->setKeyValue('Digits', $Digits);
    }
    public function getDigits()
    {
        return $this->getValue('Digits', 2);
    }
    public function getFieldView()
    {
        return 'sokeio::components.field.number';
    }
}
