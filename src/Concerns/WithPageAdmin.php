<?php

namespace Sokeio\Concerns;


trait WithPageAdmin
{
    public static function pageAdmin()
    {
        return true;
    }
    public static function pageAuth()
    {
        return true;
    }
}
