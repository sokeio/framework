<?php

namespace Sokeio\Components\Field;

use Sokeio\Components\Field\Concerns\WithDatasource;

class RadioMutilField extends BaseField
{
    use WithDatasource;
    public function getFieldView()
    {
        return 'sokeio::components.field.radio-multiple';
    }
}
