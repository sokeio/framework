<?php

namespace Sokeio;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sokeio\Support\Platform
 *
 * @method static mixed version()
 * @method static void loadFromPath($path)
 * @method static void loadFromServicePackage($package)
 * @method static void addLoader($loader)
 * @method static void applyLoader(\Sokeio\Support\Platform\ItemInfo $item)
 * @method static void booting($callback)
 * @method static void boot()
 * @method static void booted($callback)
 * @method static \Sokeio\Support\Platform\ItemManager module()
 * @method static \Sokeio\Support\Platform\ItemManager theme()
 * @method static string adminUrl()
 * @method static bool isUrlAdmin()
 * @method static bool isVendor($path)
 * @method static string getPlatformPath()
 * @method static string currentUrl();
 * @method static void routeWeb($group,$isAuth=false)
 * @method static void routeAdmin($group,$isGuest = false)
 * @method static void routeApi($group,$isGuest = false)
 * @method static \Sokeio\Support\Platform\GateManager gate()
 * @method static void scanAllClass($directory, $namespace, callable $callback = null, callable $filter = null)
 * @method static void runLoader(\Sokeio\Support\Platform\ItemInfo $item, $path, $namespace, $aliasPrefix = '')
 * @method static string logoFull()
 * @method static string logo()
 * @method static mixed getThemeSite()
 * @method static \Sokeio\Support\Platform\PlatformManager registerModel($class, ItemInfo $itemInfo)
 * @method static array getAllModel()
 */
class Platform extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sokeio_platform';
    }
}
