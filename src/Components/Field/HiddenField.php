<?php

namespace Sokeio\Components\Field;

class HiddenField extends BaseField
{
    public function getView()
    {
        return 'sokeio::components.field.hidden';
    }
}
