<?php

use Illuminate\Support\Facades\Route;
use Sokeio\Platform;

Route::get('test1', function () {
    return Platform::gate()->getLoaders();
});
