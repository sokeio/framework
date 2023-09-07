<?php

namespace BytePlatform\Actions;

use BytePlatform\Traits\WithAction;
use Illuminate\Http\Request;

class HelloWord
{
    use WithAction;
    public function DoAction($slug)
    {
        return 'hello world' . $slug;
    }
}
