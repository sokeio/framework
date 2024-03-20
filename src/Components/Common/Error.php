<?php

namespace Sokeio\Components\Common;

use Sokeio\Components\Common\Concerns\WithName;
use Sokeio\Components\UI;

class Error extends BaseCommon
{
    use WithName;
    public function getFormField()
    {
        $field = $this->getName();
        if ($this->checkPrex()) {
            $operator = '';
            $field = $this->getPrex() . '.';
            $field .= ($operator != '' ?  $operator . '.' : '');
            $field .= str_replace('.', UI::KEY_FIELD_NAME, $this->getName());
        }
        return $field;
    }
    protected function __construct($value)
    {
        $this->name($value);
    }
    public function getView()
    {
        return 'sokeio::components.common.error';
    }
}
