<?php

namespace Sokeio\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mix getLastVersion($arrs = [], $type = 'module')
 * @method static void sokeioInstall()
 * @method static mix checkLicenseKey($key)
 * @method static mix checkLicense()
 * @method static mix getLicense()
 * @method static void savePackage()
 * @method static string getProductId()
 * @see \Sokeio\Facades\Client
 */
class Client extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Sokeio\Platform\ClientManager::class;
    }
}
