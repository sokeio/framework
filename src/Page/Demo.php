<?php

namespace Sokeio\Page;

use Sokeio\page;
use Sokeio\UI\Common\Button;
use Sokeio\UI\WithUI;

class Demo extends page
{
    use WithUI;
    public function alert($message)
    {
        $this->sendMessage($message);
    }
    public function setupUI()
    {
        return [
            Button::init()->text('Click Me')->wireClick('alert("Hello World")'),
        ];
    }
}
