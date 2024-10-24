<?php

namespace Sokeio\Components\Field;

use Sokeio\Components\Field\Concerns\WithDatasource;

class SelectField extends BaseField
{
    use WithDatasource;
    public function boot()
    {
        parent::boot();
        $this->convertEmptyStringsToNull();
    }
    public function getFieldView()
    {
        return 'sokeio::components.field.select';
    }
}
