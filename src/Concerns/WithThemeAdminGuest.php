<?php

namespace Sokeio\Concerns;


trait WithThemeAdminGuest
{
    protected static function isThemeAdmin()
    {
        return true;
    }
    protected static function isAuth()
    {
        return false;
    }
}
