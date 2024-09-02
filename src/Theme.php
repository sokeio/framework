<?php

namespace Sokeio;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sokeio\Theme
 * @method static Sokeio\Theme\ThemeManager title($title);
 * @method static Sokeio\Theme\ThemeManager description($description);
 * @method static Sokeio\Theme\ThemeManager keywords($keywords);
 * @method static mixed getSiteInfo();
 * @method static mixed getTheme();
 * @method static mixed getThemeAdmin();
 * @method static mixed getThemeSite();
 * 
 * @method static mixed view($view, $data = [], $mergeData = [])
 * @method static void include($view, $data = [], $mergeData = [])
 * @method static string getLayout($default=null)
 * @method static string getNamespace($isAdmin = false)
 * @method static Sokeio\Theme\ThemeManager enableCdn();
 * @method static Sokeio\Theme\ThemeManager disableCdn();
 * @method static Sokeio\Theme\ThemeManager js($content);
 * @method static Sokeio\Theme\ThemeManager jsFromPath($path);
 * @method static Sokeio\Theme\ThemeManager style($content);
 * @method static Sokeio\Theme\ThemeManager styleFromPath($path)
 * @method static Sokeio\Theme\ThemeManager linkCss($link, $cdn = null);
 * @method static Sokeio\Theme\ThemeManager linkJs($link, $cdn = null);
 * @method static Sokeio\Theme\ThemeManager bodyBefore($callback);
 * @method static Sokeio\Theme\ThemeManager bodyAfter($callback);
 * @method static Sokeio\Theme\ThemeManager headBefore($callback);
 * @method static Sokeio\Theme\ThemeManager headAfter($callback);
 * @method static void headRender()
 * @method static void bodyRender()
 * @method static void bodyEndRender()
 *
 */
class Theme extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sokeio_theme';
    }
}
