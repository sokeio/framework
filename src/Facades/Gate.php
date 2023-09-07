<?php

namespace BytePlatform\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mix check($key)
 * @method static void BootApp()
 *
 * @see \BytePlatform\Facades\Gate
 */
class Gate extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \BytePlatform\Support\Core\GateManager::class;
    }
}
