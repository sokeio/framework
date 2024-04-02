<?php

namespace Sokeio\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mix getLastVersion($arrs = [], $type = 'module')
 * @method static void sokeioInstall()
 * @see \Sokeio\Facades\Updater
 */
class Updater extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Sokeio\Updater\UpdaterManager::class;
    }
}
