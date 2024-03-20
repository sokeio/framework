<?php

namespace Sokeio\Components\Field\Concerns;

trait WithFieldOption
{
    public function fieldOption($fieldOption): static
    {
        return $this->setKeyValue('fieldOption', $fieldOption);
    }
    public function getFieldOption()
    {
        return $this->getValue('fieldOption', []);
    }
}
