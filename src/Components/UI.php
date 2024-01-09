<?php

namespace Sokeio\Components;

use Illuminate\Support\Traits\Macroable;
use Sokeio\Components\Concerns\WithCommon;
use Sokeio\Components\Concerns\WithGrid;
use Sokeio\Components\Concerns\WithField;

class UI
{
    public const KEY_FIELD_NAME = '###______###';
    use Macroable, WithGrid, WithCommon, WithField;
}
