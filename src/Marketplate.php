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
 * @method static mixed cacheProductInfo()
 *
 */

class Marketplate extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MarketplateManager::class;
    }
}
