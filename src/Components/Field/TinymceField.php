<?php

namespace Sokeio\Components\Field;

use Sokeio\Components\Field\Concerns\WithFieldOption;

class TinymceField extends BaseField
{
    use WithFieldOption;
    public function getFieldView()
    {
        return 'sokeio::components.field.tinymce';
    }
}
