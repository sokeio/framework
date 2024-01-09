<?php

namespace Sokeio\Components\Field\Concerns;

trait WithFieldOption
{
    public function FieldOption($FieldOption): static
    {
        return $this->setKeyValue('FieldOption', $FieldOption);
    }
    public function getFieldOption()
    {
        return $this->getValue('FieldOption', []);
    }
}
