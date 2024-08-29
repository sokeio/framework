<?php

namespace Sokeio;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sokeio\Platform
 *
 * @method static mixed version()
 * @method static void loadFromPath($path)
 * @method static void loadFromServicePackage($package)
 * @method static void addLoader($loader)
 * @method static void applyLoader(\Sokeio\Platform\ItemInfo $item)
 * @method static void booting($callback)
 * @method static void boot()
 * @method static void booted($callback)
 * @method static \Sokeio\Platform\ItemManager module()
 * @method static \Sokeio\Platform\ItemManager theme()
 * @method static bool isUrlAdmin()
 * @method static bool isVendor($path)
 * @method static string getPlatformPath()
 * @method static string currentUrl();
 * @method static void routeWeb($group,$isAuth=false)
 * @method static void routeAdmin($group,$isGuest = false)
 * @method static void routeApi($group,$isGuest = false)
 * @method static \Sokeio\Platform\GateManager gate()
 * @method static void scanAllClass($directory, $namespace, callable $callback = null, callable $filter = null)
 * @method static void runLoader(\Sokeio\Platform\ItemInfo $item, $path, $namespace, $aliasPrefix = '')
 */
class Platform extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sokeio_platform';
    }
}
