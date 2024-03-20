<?php

namespace Sokeio\Components\Field;


class ImageField extends BaseField
{
    public function mutil($mutil = true): static
    {
        return $this->setKeyValue('mutil', $mutil);
    }
    public function getMutil()
    {
        return $this->getValue('mutil');
    }
    public function getFieldView()
    {
        return 'sokeio::components.field.image';
    }
}
