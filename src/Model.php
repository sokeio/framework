<?php

namespace BytePlatform;

use BytePlatform\Concerns\WithModelHook;

class Model extends \Illuminate\Database\Eloquent\Model
{
    use WithModelHook;
}
