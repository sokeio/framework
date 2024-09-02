<?php

namespace Sokeio\Concerns;


trait WithPageAdminGuest
{
    use WithPageAdmin;
    public static function pageAuth()
    {
        return false;
    }
}
