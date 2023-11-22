<?php

namespace Sokeio\Events;

use Illuminate\Foundation\Events\Dispatchable;

class NotificationAdd
{
    use Dispatchable;
    public $item;
    public function __construct($item)
    {
        $this->item = $item;
    }
}
