<?php

namespace Sokeio\Admin\Components\Field\Concerns;

trait WithDatasource
{
    public function DataSource($DataSource): static
    {
        return $this->setKeyValue('DataSource', $DataSource);
    }
    public function getDataSource()
    {
        return $this->getValue('DataSource');
    }
    public function FieldKey($FieldKey): static
    {
        return $this->setKeyValue('FieldKey', $FieldKey);
    }
    public function getFieldKey()
    {
        return $this->getValue('FieldKey', 'id');
    }
    public function FieldText($FieldText): static
    {
        return $this->setKeyValue('FieldText', $FieldText);
    }
    public function getFieldText()
    {
        return $this->getValue('FieldText', 'name');
    }
}
