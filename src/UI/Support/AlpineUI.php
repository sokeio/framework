<?php

namespace Sokeio\UI\Support;

use Sokeio\UI\BaseUI;
use Sokeio\UI\Support\Concerns\WithAlpine;

class AlpineUI
{
    use WithAlpine;
    public function __construct(protected BaseUI $ui) {}
    public function ui()
    {
        return $this->ui;
    }
}
