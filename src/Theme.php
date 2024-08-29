<?php

namespace Sokeio;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sokeio\Theme
 * @method static void title($title);
 * @method static void description($description);
 * @method static void keywords($keywords);
 * @method static mixed getSiteInfo();
 * @method static mixed view($view, $data = [], $mergeData = [])
 * @method static void enableCdn();
 * @method static void disableCdn();
 * @method static void js($content);
 * @method static void style($content);
 * @method static void linkCss($link, $cdn = null);
 * @method static void linkJs($link, $cdn = null);
 * @method static void bodyBefore($callback);
 * @method static void bodyAfter($callback);
 * @method static void headBefore($callback);
 * @method static void headAfter($callback);
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
