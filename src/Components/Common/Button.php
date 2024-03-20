<?php

namespace Sokeio\Components\Common;

use Sokeio\Components\Common\Concerns\WithButtonBasic;
use Sokeio\Components\Common\Concerns\WithButtonColor;
use Sokeio\Components\Common\Concerns\WithButtonSoke;
use Sokeio\Components\Common\Concerns\WithButtonWire;
use Sokeio\Components\Concerns\WithColumn;

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
        $this->dataItem($row);
        if ($this->fieldValueCallback) {
            return call_user_func($this->fieldValueCallback, $row, $this, $this->getManager());
        }
        $this->title(data_get($row, $this->getName()));
        return view($this->getView(), [
            'column' => $this
        ])->render();
    }
    public function getView()
    {
        return 'sokeio::components.common.button';
    }
}
