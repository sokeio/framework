<?php

namespace Sokeio\Components\Field;

use Sokeio\Components\Field\Concerns\WithDatasource;

class SelectWithSearchField extends BaseField
{
    use WithDatasource;
    public function SearchDataSource($SearchDataSource): static
    {
        return $this->setKeyValue('SearchDataSource', $SearchDataSource);
    }
    public function getSearchDataSource()
    {
        return $this->getValue('SearchDataSource');
    }
    
    public function getFieldView()
    {
        return 'sokeio::components.field.select-with-search';
    }
}
