<?php

namespace Sokeio\Admin\Components\Field;

use Sokeio\Admin\Components\Field\Concerns\WithFieldOption;
use Sokeio\Admin\Components\Field\Concerns\WithFieldRange;

class DatePickerField extends BaseField
{
    use WithFieldOption, WithFieldRange;
    public function getFieldView()
    {
        return 'sokeio::components.field.flatpickr';
    }
}
