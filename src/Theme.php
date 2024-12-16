<?php

namespace Sokeio;

use Illuminate\Support\Facades\Facade;
use Sokeio\Support\Theme\ThemeManager;

/**
 * @see \Sokeio\Support\Theme
 * @method static \Sokeio\Support\Theme\ThemeManager title($title);
 * @method static \Sokeio\Support\Theme\ThemeManager description($description);
 * @method static \Sokeio\Support\Theme\ThemeManager keywords($keywords);
 * @method static mixed getSiteInfo();
 * @method static \Sokeio\Support\Platform\ItemInfo getTheme();
 * @method static \Sokeio\Support\Platform\ItemInfo getThemeAdmin();
 * @method static \Sokeio\Support\Platform\ItemInfo getThemeSite();
 * @method static mixed view($view, $data = [], $mergeData = [], $noScope = false)
 * @method static void include($view, $data = [], $mergeData = [])
 * @method static void setLayout(string $layout='none', $isAdmin = null)
 * @method static string getLayout($default=null)
 * @method static string getNamespace($isAdmin = false)
 * @method static \Sokeio\Support\Theme\ThemeManager enableCdn();
 * @method static \Sokeio\Support\Theme\ThemeManager disableCdn();
 * @method static \Sokeio\Support\Theme\ThemeManager template($content, $id = null);
 * @method static \Sokeio\Support\Theme\ThemeManager templateFromPath($path, $id = null);
 * @method static \Sokeio\Support\Theme\ThemeManager js($content, $id = null);
 * @method static \Sokeio\Support\Theme\ThemeManager jsFromPath($path, $id = null);
 * @method static \Sokeio\Support\Theme\ThemeManager style($content, $id = null);
 * @method static \Sokeio\Support\Theme\ThemeManager styleFromPath($path, $id = null);
 * @method static \Sokeio\Support\Theme\ThemeManager linkCss($link, $cdn = null);
 * @method static \Sokeio\Support\Theme\ThemeManager linkJs($link, $cdn = null);
 * @method static \Sokeio\Support\Theme\ThemeManager bodyBefore($callback);
 * @method static \Sokeio\Support\Theme\ThemeManager bodyAfter($callback);
 * @method static \Sokeio\Support\Theme\ThemeManager headBefore($callback);
 * @method static \Sokeio\Support\Theme\ThemeManager headAfter($callback);
 * @method static void headRender()
 * @method static void bodyRender()
 * @method static void bodyEndRender()
 * @method static void renderLocation($location)
 * @method static void location($location, $callback)
 * @method static \Sokeio\Support\Theme\ThemeManager setOptions($options)
 * @method static \Sokeio\Support\Theme\ThemeManager|string|number|array|null option($key = null, $default = null)
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
