<?php

namespace Sokeio\Components\Field;

use Sokeio\Components\Base;
use Sokeio\Components\Common\Concerns\WithColumnGrid;
use Sokeio\Components\Concerns\WithColumn;
use Sokeio\Components\Field\Concerns\WithFieldBase;
use Sokeio\Components\Field\Concerns\withFieldOperator;
use Sokeio\Components\Field\Concerns\WithFieldRule;
use Sokeio\Components\Field\Concerns\WithFieldWire;

class BaseField extends Base
{
    use WithFieldWire, WithFieldRule, WithFieldBase, withFieldOperator, WithColumn;
    use WithColumnGrid;
    protected function ChildComponents()
    {
        return [
            $this->getUIBefore(),
            $this->getUIAfter()
        ];
    }
    public function getView()
    {
        return 'sokeio::components.layout-field';
    }
    public function boot()
    {
        parent::boot();
        if (!$this->getNoSave() && method_exists($this->getManager(), 'addInputUI')) {
            $this->getManager()?->addInputUI($this, $this->getPrex() ?? 'data');
        }
    }
    protected function __construct($value)
    {
        $this->Name($value);
    }
}
