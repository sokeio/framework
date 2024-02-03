<?php

namespace Sokeio\Components\Field\Concerns;

trait WithDatasource
{
    public function TextNoData($TextNoData): static
    {
        return $this->setKeyValue('TextNoData', $TextNoData);
    }
    public function getTextNoData()
    {
        return $this->getValue('TextNoData', __('No results found'));
    }
    public function DataSource($DataSource): static
    {
        return $this->setKeyValue('DataSource', $DataSource);
    }
    public function getDataSource()
    {
        return $this->getValue('DataSource');
    }

    public function ViewTemplate($ViewTemplate): static
    {
        return $this->setKeyValue('ViewTemplate', $ViewTemplate);
    }
    public function getViewTemplate()
    {
        return $this->getValue('ViewTemplate');
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
