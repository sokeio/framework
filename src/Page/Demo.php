<?php

namespace Sokeio\Page;

use Illuminate\Support\Facades\Log;
use Sokeio\page;
use Sokeio\UI\Common\Button;
use Sokeio\UI\WithUI;

class Demo extends page
{
    use WithUI;
    public function alert($message)
    {
        Log::debug('Alert: ' . $message);
        $this->sendMessage($message);
    }
    public function setupUI()
    {
        return [
            Button::init()->text('Click Me')->wireClick(function () {
                $this->alert('Hello World');
            }, 'demo_click'),
        ];
    }
}
