<?php

namespace BytePlatform\Events;

use Illuminate\Foundation\Events\Dispatchable;

class PlatformStatusChanged
{
    use Dispatchable;
    public $item;
    public function __construct($item)
    {
        $this->item = $item;
    }
}
