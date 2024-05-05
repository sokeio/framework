<?php

namespace Sokeio\Components\Field;

use Sokeio\Components\Field\Concerns\WithFieldOption;
use Sokeio\Components\Field\Concerns\WithFieldRelation;

class TagifyField extends BaseField
{
    use WithFieldOption, WithFieldRelation;
    public function getFieldView()
    {
        return 'sokeio::components.field.tagify';
    }
}
