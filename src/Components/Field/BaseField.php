<?php

namespace Sokeio\Admin\Components\Field;

use Sokeio\Admin\Components\Base;
use Sokeio\Admin\Components\Concerns\WithColumn;
use Sokeio\Admin\Components\Field\Concerns\WithFieldBase;
use Sokeio\Admin\Components\Field\Concerns\withFieldOperator;
use Sokeio\Admin\Components\Field\Concerns\WithFieldRule;
use Sokeio\Admin\Components\Field\Concerns\WithFieldWire;

class BaseField extends Base
{
    use WithFieldWire, WithFieldRule, WithFieldBase, withFieldOperator, WithColumn;
    public function getView()
    {
        return 'sokeio::components.layout-field';
    }
    public function boot()
    {
        if (!$this->getNoSave()){
            $this->getManager()?->addColumn($this);
        }
        parent::boot();
    }
    protected function __construct($value)
    {
        $this->Name($value);
    }
}
