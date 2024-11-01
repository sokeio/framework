<?php

use Sokeio\Platform;
use Sokeio\Theme;

if (!function_exists('theme')) {
    function theme()
    {
        return Theme::getFacadeRoot();
    }
}
if (!function_exists('platform')) {
    function platform()
    {
        return Platform::getFacadeRoot();
    }
}
