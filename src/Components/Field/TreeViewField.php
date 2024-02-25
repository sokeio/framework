<?php

namespace Sokeio\Components\Field;

use Sokeio\Components\Field\Concerns\WithDatasource;

class TreeViewField extends BaseField
{
    use WithDatasource;
    public function getFieldView()
    {
        return 'sokeio::components.field.tree-view';
    }
}
