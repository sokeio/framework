<?php

namespace Sokeio\Components\Field;

use Sokeio\Components\Field\Concerns\WithDatasource;

class SelectWithSearchField extends BaseField
{
    use WithDatasource;
    public function searchFn($searchFn): static
    {
        return $this->setKeyValue('searchFn', $searchFn);
    }
    public function getSearchFn()
    {
        return $this->getValue('searchFn');
    }

    public function getFieldView()
    {
        return 'sokeio::components.field.select-with-search';
    }
}
