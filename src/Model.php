<?php

namespace Sokeio;

use Sokeio\Core\Concerns\WithModelHook;

if (class_exists('\MongoDB\Laravel\Eloquent\Model')) {
    class Model extends \MongoDB\Laravel\Eloquent\Model
    {
        use WithModelHook;
    }
} else {
    class Model extends \Illuminate\Database\Eloquent\Model
    {
        use WithModelHook;
    }
}
