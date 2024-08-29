<?php

namespace Sokeio\Concerns;


trait WithPageAdminGuest
{
    public static function pageAdmin()
    {
        return true;
    }
    public static function pageAuth()
    {
        return false;
    }
}
