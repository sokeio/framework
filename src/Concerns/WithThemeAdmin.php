<?php

namespace Sokeio\Concerns;


trait WithThemeAdmin
{
    protected static function isThemeAdmin()
    {
        return true;
    }
    protected static function isAuth()
    {
        return true;
    }
}
