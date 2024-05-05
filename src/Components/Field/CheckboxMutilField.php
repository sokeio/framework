<?php

namespace Sokeio\Components\Field;

use Sokeio\Components\Field\Concerns\WithDatasource;
use Sokeio\Components\Field\Concerns\WithFieldRelation;

class CheckboxMutilField extends BaseField
{
    use WithDatasource, WithFieldRelation;
    public function classCell($classCell = 'col-12'): static
    {
        return $this->setKeyValue('classCell', $classCell);
    }
    public function getClassCell()
    {
        return $this->getValue('classCell');
    }
    public function getFieldView()
    {
        return 'sokeio::components.field.checkbox-multiple';
    }
}
