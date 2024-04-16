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
    public function getView()
    {
        return 'sokeio::components.common.button';
    }
}
