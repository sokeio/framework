<?php

namespace Sokeio\Components\Field;

use Sokeio\Components\Field\Concerns\WithCheckbox;

class ToggleField extends BaseField
{
    use WithCheckbox;
    public function getFieldView()
    {
        return 'sokeio::components.field.toggle';
    }
}
