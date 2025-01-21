<?php

namespace Sokeio;

use Illuminate\Support\Facades\Facade;
use Sokeio\Marketplate\MarketplateManager;

/**
 * @see \Sokeio\Marketplate
 * 
 * @method static bool checkNewVersion()
 * @method static mixed getNewVersionInfo()
 * @method static void saveProductInfo()
 * @method static mixed getProductInfo()
 * @method static void updateNow($callback = null, $secret=null)
 * @method static void getProvider($key, $data)
 * @method static void registerProvider($key, $provider)
 * @method static bool verifyLicense($license, $productId)
 * @method static array getLicense()
 *
 */

class Marketplate extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MarketplateManager::class;
    }
}
