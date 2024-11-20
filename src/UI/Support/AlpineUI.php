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
    public function __get($name)
    {
        if (method_exists($this, $name)) {
            return $this->$name();
        }
        return $this->ui->$name;
    }
}
