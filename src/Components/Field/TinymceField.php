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
    public function getSkipOption()
    {
        return $this->getValue('skipOption');
    }
    public function skipOption()
    {
        return $this->setKeyValue('skipOption', true);
    }
}
