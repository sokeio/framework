<?php

namespace Sokeio\Pattern;

trait Tap
{
    public function tap($callback)
    {
        $callback($this);
        return $this;
    }
}
