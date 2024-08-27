<?php

namespace Sokeio;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sokeio\Platform
 *
 * @method static void loadFromPath($path)
 * @method static void loadFromServicePackage($package)
 * @method static void addLoader($loader)
 * @method static void applyLoader(\Sokeio\Platform\ItemInfo $data)
 * @method static void booting($callback)
 * @method static void boot()
 * @method static void booted($callback)
 * @method static \Sokeio\Platform\ItemManager module()
 * @method static \Sokeio\Platform\ItemManager theme()
 * @method static bool isUrlAdmin()
 * @method static bool isVendor($path)
 * @method static string getPath();
 */
class Platform extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sokeio_platform';
    }
}
