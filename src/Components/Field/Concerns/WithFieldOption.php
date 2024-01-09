<?php

namespace Sokeio\Admin\Components\Field\Concerns;

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
