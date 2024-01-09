<?php

namespace Sokeio\Admin\Components\Field;

use Sokeio\Admin\Components\Field\Concerns\WithDatasource;

class ToggleMutilField extends BaseField
{
    use WithDatasource;
    public function getFieldView()
    {
        return 'sokeio::components.field.toggle-multiple';
    }
}
