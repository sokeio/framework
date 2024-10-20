<?php

namespace Sokeio\Components\Field;

use Sokeio\Components\Field\Concerns\WithFieldOption;
use Sokeio\Components\Field\Concerns\WithFieldRange;

class DatePickerField extends BaseField
{
    use WithFieldOption, WithFieldRange;
    public function boot()
    {
        parent::boot();
        $this->convertEmptyStringsToNull();
    }
    public function getFieldView()
    {
        return 'sokeio::components.field.datepicker';
    }
}
