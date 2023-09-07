<?php

namespace BytePlatform\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * 
 * @method static void register($shortcodes, $namespace, $force = false)
 * @method static void registerItem($key, $itemOrCallback, $namespace, $force = false)
 * @method static mix getShortCodes()
 * @method static mix getShortCodeByKey($key)
 * @method static string compile(string $content)
 * @method static string strip(string $content)
 * @method static void disable()
 * @method static void enable()
 * @method static \BytePlatform\Shortcode\ShortcodeItem create($name)
 * 
 *
 * @see \BytePlatform\Facades\Shortcode
 */
class Shortcode extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "shortcode";
    }
}
