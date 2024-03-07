<?php

namespace Sokeio\Components\Field;

use Sokeio\Components\Field\Concerns\WithCheckbox;

class CheckboxField extends BaseField
{
    use WithCheckbox;
    public function getFieldView()
    {
        return 'sokeio::components.field.checkbox';
    }
}
