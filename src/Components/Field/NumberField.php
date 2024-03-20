<?php

namespace Sokeio\Components\Field;

use Sokeio\Components\Field\Concerns\WithFieldRange;

class NumberField extends BaseField
{
    use WithFieldRange;
    public function digits($digits): static
    {
        return $this->setKeyValue('digits', $digits);
    }
    public function getDigits()
    {
        return $this->getValue('digits', 2);
    }
    public function getFieldView()
    {
        return 'sokeio::components.field.number';
    }
}
