<?php

namespace Sokeio\Pattern;

trait Tap
{
    public function tap($callback)
    {
        if ($callback) {
            $callback($this);
        }
        return $this;
    }
}
