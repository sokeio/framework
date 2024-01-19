<?php

namespace Sokeio\Components\Common;

use Sokeio\Components\Field\Concerns\WithFieldBase;
use Sokeio\Components\UI;

class Error extends BaseCommon
{
    public function Name($Name): static
    {
        return $this->setKeyValue('Name', $Name);
    }
    public function getName()
    {
        return $this->getValue('Name');
    }
    public function getFormField()
    {
        if ($this->checkPrex()) {
            $operator = '';
            return $this->getPrex() . '.' . ($operator != '' ?  $operator . '.' : '') . str_replace('.', UI::KEY_FIELD_NAME, $this->getName());
        }
        return $this->getName();
    }
    protected function __construct($value)
    {
        $this->Name($value);
    }
    public function getView()
    {
        return 'sokeio::components.common.error';
    }
}
