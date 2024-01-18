<?php

namespace Sokeio\Components\Field;

use Sokeio\Components\Base;
use Sokeio\Components\Concerns\WithColumn;
use Sokeio\Components\Field\Concerns\WithFieldBase;
use Sokeio\Components\Field\Concerns\withFieldOperator;
use Sokeio\Components\Field\Concerns\WithFieldRule;
use Sokeio\Components\Field\Concerns\WithFieldWire;

class BaseField extends Base
{
    use WithFieldWire, WithFieldRule, WithFieldBase, withFieldOperator, WithColumn;
    public function getView()
    {
        return 'sokeio::components.layout-field';
    }
    public function boot()
    {
        if (!$this->getNoSave()) {
            $this->getManager()?->addInputUI($this, $this->getPrex() ?? 'data');
        }
        parent::boot();
    }
    protected function __construct($value)
    {
        $this->Name($value);
    }
}
