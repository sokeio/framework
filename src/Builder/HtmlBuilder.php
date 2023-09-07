<?php

namespace BytePlatform\Builder;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Traits\Macroable;

abstract class HtmlBuilder implements Htmlable
{
    use Macroable;
    public function genId($prev, $min = 1000, $max = 100000000)
    {
        $this->ElId = $prev . rand($min, $max);
    }
    private $ElId = '';
    public function getId()
    {
        return $this->ElId;
    }
    protected abstract function render();
    public function toHtml()
    {
        ob_start();
        $this->render();
        return ob_get_clean();
    }
    public function __toString()
    {
        return $this->toHtml();
    }
}