<?php

namespace Sokeio;

use Illuminate\Support\Facades\Facade;
use Sokeio\Core\PlatformManager;

/**
 * @see \Sokeio\Platform
 *
 * @method static mixed version()
 * @method static void loadFromPath($path)
 * @method static void loadFromServicePackage($package)
 * @method static void addLoader($loader)
 * @method static void applyLoader(\Sokeio\Core\ItemInfo $item)
 * @method static void booting($callback)
 * @method static void boot()
 * @method static void booted($callback)
 * @method static \Sokeio\Core\ItemManager module()
 * @method static \Sokeio\Core\ItemManager theme()
 * @method static \Sokeio\Menu\MenuManager menu()
 * @method static string adminUrl()
 * @method static bool isUrlAdmin()
 * @method static bool isVendor($path)
 * @method static string getPlatformPath()
 * @method static string currentUrl();
 * @method static void routeWeb($group,$isAuth=false)
 * @method static void routeAdmin($group,$isGuest = false)
 * @method static void routeApi($group,$isGuest = false)
 * @method static \Sokeio\Core\GateManager gate()
 * @method static void scanAllClass($directory, $namespace, callable $callback = null, callable $filter = null)
 * @method static void runLoader(\Sokeio\Core\ItemInfo $item, $path, $namespace, $aliasPrefix = '')
 * @method static string logoFull()
 * @method static string logo()
 * @method static mixed getThemeSite()
 * @method static \Sokeio\Core\PlatformManager registerModel($class, ItemInfo $itemInfo)
 * @method static array getAllModel()
 * @method static array getModelByKey($modelKey, $paramKey = null)
 * @method static mixed apiOk($data = null,$message = null, $code = 200)
 * @method static mixed apiError($message = null, $errors = [], $code = 500)
 * @method static void artisanInBackground($command, $data = null)
 * @method static string randomKey($length = 24, $groupLength = 6, $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789')
 */

class Platform extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PlatformManager::class;
    }
}
