<?php

namespace Sokeio\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void enableCachePage()
 * @method static void disableCachePage()
 * @method static bool checkCachePage()
 * @method static mixed registerComposer($path, $resgister = false)
 * @method static void addLink(string $source,string $target, bool $relative = false)
 * @method static mix getLinks()
 * @method static void makeLink()
 * @method static mix getExtends()
 * @method static void checkFolderPlatform()
 * @method static mix getDataInfo($path,$register)
 * @method static array getModels()
 * @method static void NotificationAdd($title,$description,$meta_data,$to_role,$to_user)
 * @method static void bootGate()
 * @method static void routeAdminBeforeReady($callback = null)
 * @method static void doRouteAdminBeforeReady()
 * @method static void routeSiteBeforeReady($callback = null)
 * @method static void doRouteSiteBeforeReady()
 * @method static void routeApiBeforeReady($callback = null)
 * @method static void doRouteApiBeforeReady()
 * @method static void Ready($callback = null)
 * @method static void DoReady()
 * @method static void ReadyAfter($callback = null)
 * @method static void DoReadyAfter()
 * @method static bool checkGate();
 * @method static bool checkConnectDB();
 * @method static void setEnv($arrs)
 * @method static void start();
 * @method static long executionTime();
 * @method static void cleanThemeAdmin
 * @method static bool isThemeAdmin
 * @method static string keyAdmin()
 * @see \Sokeio\Facades\Platform
 */
class Platform extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Sokeio\Platform\PlatformManager::class;
    }
}
