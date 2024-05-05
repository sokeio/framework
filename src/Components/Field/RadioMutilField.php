<?php

namespace Sokeio\Components\Field;

use Sokeio\Components\Field\Concerns\WithDatasource;
use Sokeio\Components\Field\Concerns\WithFieldRelation;

class RadioMutilField extends BaseField
{
    use WithDatasource,WithFieldRelation;
    public function getFieldView()
    {
        return 'sokeio::components.field.radio-multiple';
    }
}
