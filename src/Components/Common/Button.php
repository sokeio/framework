<?php

namespace Sokeio\Admin\Components\Common;

use Sokeio\Admin\Components\Common\Concerns\WithButtonBasic;
use Sokeio\Admin\Components\Common\Concerns\WithButtonColor;
use Sokeio\Admin\Components\Common\Concerns\WithButtonSoke;
use Sokeio\Admin\Components\Common\Concerns\WithButtonWire;
use Sokeio\Admin\Components\Concerns\WithColumn;

class Button extends BaseCommon
{
    use WithButtonBasic, WithButtonColor, WithButtonWire, WithButtonSoke, WithColumn;
    protected function __construct($value)
    {
        $this->Name($value);
    }

    public function getFieldValue($row)
    {
        $this->ClearCache();
        $this->DataItem($row);
        if ($this->fieldValueCallback) return call_user_func($this->fieldValueCallback, $row, $this, $this->getManager());
        $this->Title(data_get($row, $this->getName()));
        return view($this->getView(), [
            'column' => $this
        ])->render();
    }
    public function getView()
    {
        return 'sokeio::components.common.button';
    }
}
