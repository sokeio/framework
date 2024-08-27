<?php

namespace Sokeio;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sokeio\Platform
 *
 * @method static void loadFromPath($path)
 * @method static void loadFromServicePackage($package)
 * @method static void loader()
 * @method static void booting($callback)
 * @method static void boot()
 * @method static void booted($callback)
 * @method static \Sokeio\Platform\ItemManager module()
 * @method static \Sokeio\Platform\ItemManager theme()
 * @method static bool isUrlAdmin()
 * @method static string platformPath();
 */

class Platform extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sokeio_platform';
    }
}
