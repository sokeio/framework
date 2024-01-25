<?php

namespace Sokeio\Facades;

use Illuminate\Support\Facades\Facade;
use Sokeio\Platform\ThemeManager;

/**
 * 
 * @method static string getName()
 * @method static string FileInfoJson()
 * @method static string HookFilterPath()
 * @method static string PathFolder()
 * @method static string getPath(string $path)
 * @method static string PublicFolder()
 * @method static void LoadApp()
 * @method static void RegisterApp()
 * @method static void BootApp()
 * @method static \Illuminate\Support\Collection<string, \Sokeio\Platform\DataInfo> getAll()
 * @method static \Sokeio\Platform\DataInfo find(string $name)
 * @method static bool has(string $name)
 * @method static void delete(string $name)
 * @method static void Load(string $path)
 * @method static void AddItem(string $path)
 * @method static string getUsed()
 * @method static void forgetUsed()
 * @method static void setUsed(string $name)
 * @method static void update(string $name)
 * @method static string Layout($default='')
 * @method static mix ThemeCurrent()
 * @method static void RegisterTheme()
 * @method static void setLayout($default='')
 * @method static ThemeManager DisableHtmlAjax()
 * @method static ThemeManager enableHtmlAjax()
 * @method static void reTheme()
 * @method static void RegisterRoute();
 * @method static string AdminId()
 * @method static string SiteId()
 * @method static mixed addListener(string|array $hook, mixed $callback,int  $priority)
 * @method static ThemeManager removeListener(string  $hook)
 * @method static array getListeners()
 * @method static mixed fire(string  $action,array  $args)
 * @method static array getLayouts()
 * @method static array getModels()
 * @method static array getLocations()
 * @method static \Sokeio\Platform\DataInfo SiteDataInfo()
 * @method static \Sokeio\Platform\DataInfo AdminDataInfo()
 * @method static bool currentAdmin()
 * 
 * @see \Sokeio\Facades\Theme
 */
class Theme extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ThemeManager::class;
    }
}
