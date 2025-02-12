<?php

namespace Sokeio;

use Illuminate\Support\Facades\Facade;
use Sokeio\Theme\ThemeManager;

/**
 * @see \Sokeio\Theme
 * @method static \Sokeio\Theme\ThemeManager title($title);
 * @method static \Sokeio\Theme\ThemeManager description($description);
 * @method static \Sokeio\Theme\ThemeManager keywords($keywords);
 * @method static mixed getSiteInfo();
 * @method static \Sokeio\Core\ItemInfo getTheme();
 * @method static \Sokeio\Core\ItemInfo getThemeAdmin();
 * @method static \Sokeio\Core\ItemInfo getThemeSite();
 * @method static mixed view($view, $data = [], $mergeData = [], $noScope = false, $template=null)
 * @method static void include($view, $data = [], $mergeData = [])
 * @method static void setLayout(string $layout='none', $isAdmin = null)
 * @method static string getLayout($default=null)
 * @method static string getNamespace($isAdmin = false)
 * @method static \Sokeio\Theme\ThemeManager enableCdn();
 * @method static \Sokeio\Theme\ThemeManager disableCdn();
 * @method static \Sokeio\Theme\ThemeManager template($content, $id = null);
 * @method static \Sokeio\Theme\ThemeManager templateFromPath($path, $id = null);
 * @method static \Sokeio\Theme\ThemeManager js($content, $id = null);
 * @method static \Sokeio\Theme\ThemeManager jsFromPath($path, $id = null);
 * @method static \Sokeio\Theme\ThemeManager style($content, $id = null);
 * @method static \Sokeio\Theme\ThemeManager styleFromPath($path, $id = null);
 * @method static \Sokeio\Theme\ThemeManager linkCss($link, $cdn = null);
 * @method static \Sokeio\Theme\ThemeManager linkJs($link, $cdn = null);
 * @method static \Sokeio\Theme\ThemeManager bodyBefore($callback);
 * @method static \Sokeio\Theme\ThemeManager bodyAfter($callback);
 * @method static \Sokeio\Theme\ThemeManager headBefore($callback);
 * @method static \Sokeio\Theme\ThemeManager headAfter($callback);
 * @method static void headRender()
 * @method static void bodyRender()
 * @method static void bodyEndRender()
 * @method static void renderLocation($location)
 * @method static void location($location, $callback)
 * @method static \Sokeio\Theme\ThemeManager setOptions($options)
 * @method static \Sokeio\Theme\ThemeManager|string|number|array|null option($key = null, $default = null)
 * @method static mixed getTemplates()
 * @method static mixed getTemplateOptions($targets = null)
 * @method static mixed getTemplate($template, $key = null)
 * @method static mixed viewTemplate($template, $data = [], $mergeData = [], $noScope = false, $view = null)
 * @method static array getLocations()
 * @method static mixed getLocationOptions($targets = null)
 *
 */
class Theme extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ThemeManager::class;
    }
}
