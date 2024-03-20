<?php

namespace Sokeio\Components\Field\Concerns;

trait WithDatasource
{
    public function textNoData($textNoData): static
    {
        return $this->setKeyValue('textNoData', $textNoData);
    }
    public function getTextNoData()
    {
        return $this->getValue('textNoData', __('No results found'));
    }
    public function dataSource($dataSource): static
    {
        return $this->setKeyValue('dataSource', $dataSource);
    }
    public function getDataSource()
    {
        return $this->getValue('dataSource');
    }
    public function viewTemplate($viewTemplate): static
    {
        return $this->setKeyValue('viewTemplate', $viewTemplate);
    }
    public function getViewTemplate()
    {
        return $this->getValue('viewTemplate');
    }
    public function fieldKey($fieldKey): static
    {
        return $this->setKeyValue('fieldKey', $fieldKey);
    }
    public function getFieldKey()
    {
        return $this->getValue('fieldKey', 'id');
    }
    public function fieldText($fieldText): static
    {
        return $this->setKeyValue('fieldText', $fieldText);
    }
    public function getFieldText()
    {
        return $this->getValue('fieldText', 'name');
    }
}
