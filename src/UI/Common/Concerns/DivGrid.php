<?php

namespace Sokeio\UI\Common\Concerns;

trait DivGrid
{
    use WithCol;
    public function container($size = '')
    {
        return $this->className('container' . ($size ? '-' . $size : ''));
    }
    public function row()
    {
        return $this->className('row');
    }
}
