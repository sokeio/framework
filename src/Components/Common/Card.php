<?php

namespace Sokeio\Components\Common;

use Sokeio\Components\Common\Concerns\WithTitle;

class Card extends BaseCommon
{
    use WithTitle;
    public function getView()
    {
        return 'sokeio::components.common.box';
    }
}
