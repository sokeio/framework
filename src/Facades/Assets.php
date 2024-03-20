<?php

namespace Sokeio\Facades;

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
 * @method static mix getData($key,$default=null)
 * @method static void setData($key,$value)
 * @method static string getTitle()
 * @method static void setTitle($title, $lock = false)
 * @method static string getFavicon()
 * @method static void setFavicon($favicon, $lock = false)
 * @method static string getDescription()
 * @method static void setDescription($value, $lock = false)
 * @method static string getKeywords()
 * @method static void setKeywords($value, $lock = false)
 * @method static string getFavicon()
 * @method static void setFavicon($value, $lock = false)
 *
 * @see \Sokeio\Facades\Assets
 */
class Assets extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Sokeio\Platform\AssetManager::class;
    }
}
