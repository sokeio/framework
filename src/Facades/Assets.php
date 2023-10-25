<?php

namespace BytePlatform\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void AssetType($name, $baseType = 'theme')
 * @method static void Theme($name)
 * @method static void Module($name)
 * @method static void Plugin($name)
 * @method static void AddAssetType($location, $base, $name, $content, $type = self::JS, $priority = 10000)
 * @method static void AddJs($content,$base=null, $name=null, $location = PLATFORM_BODY_AFTER, $priority = 10000)
 * @method static void AddJs($content,$base=null, $name=null, $location = PLATFORM_BODY_AFTER, $priority = 10000)
 * @method static void AddCss($content,$base=null, $name=null, $location = PLATFORM_BODY_AFTER, $priority = 10000)
 * @method static void AddScript($content,$base=null, $name=null, $location = PLATFORM_BODY_AFTER, $priority = 10000)
 * @method static void AddStyle($content,$base=null, $name=null, $location = PLATFORM_BODY_AFTER, $priority = 10000)
 * @method static void Render($location)
 * @method static mix GetData($key,$default=null)
 * @method static void SetData($key,$value)
 * 
 * 
 *
 * @see \BytePlatform\Facades\Assets
 */
class Assets extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \BytePlatform\Support\Core\AssetManager::class;
    }
}
