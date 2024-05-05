<?php

namespace Sokeio\Components\Field;

use Sokeio\Components\Field\Concerns\WithDatasource;
use Sokeio\Components\Field\Concerns\WithFieldRelation;

class TreeViewField extends BaseField
{
    use WithDatasource, WithFieldRelation;
    public function getFieldView()
    {
        return 'sokeio::components.field.tree-view';
    }
}
