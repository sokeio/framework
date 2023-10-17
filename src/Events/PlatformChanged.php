<?php

namespace BytePlatform\Events;

use Illuminate\Foundation\Events\Dispatchable;

class PlatformChanged
{
    use Dispatchable;
    public $item;
    public function __construct($item)
    {
        $this->item = $item;
    }
}
