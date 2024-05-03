<?php

namespace Sokeio\Components\Field;

use Sokeio\Components\Field\Concerns\WithDatasource;
use Sokeio\Components\Field\Concerns\WithSearchFn;

class SelectWithSearchField extends BaseField
{
    use WithDatasource;
    use WithSearchFn;
    public function boot()
    {
        parent::boot();
        $this->convertEmptyStringsToNull();
    }
    public function getFieldView()
    {
        return 'sokeio::components.field.select-with-search';
    }
}
