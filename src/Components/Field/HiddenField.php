<?php

namespace Sokeio\Components\Field;

class HiddenField extends BaseField
{
    public function boot()
    {
        parent::boot();
        $this->convertEmptyStringsToNull();
    }
    public function getView()
    {
        return 'sokeio::components.field.hidden';
    }
}
