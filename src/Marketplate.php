<?php

namespace Sokeio;

use Illuminate\Support\Facades\Facade;
use Sokeio\Support\Marketplate\MarketplateManager;

/**
 * @see \Sokeio\Support\Marketplate
 * 
 * @method static bool checkNewVersion()
 * @method static mixed getNewVersionInfo()
 * @method static void saveProductInfo()
 * @method static mixed getProductInfo()
 * @method static void updateNow($callback = null, $secret=null)
 * @method static void getProvider($key, $data)
 * @method static void registerProvider($key, $provider)
 *
 */

class Marketplate extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MarketplateManager::class;
    }
}
