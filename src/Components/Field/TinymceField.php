<?php

namespace Sokeio\Admin\Components\Field;

use Sokeio\Admin\Components\Field\Concerns\WithFieldOption;

class TinymceField extends BaseField
{
    use WithFieldOption;
    public function getFieldView()
    {
        return 'sokeio::components.field.tinymce';
    }
}
