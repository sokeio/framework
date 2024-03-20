<?php

namespace Sokeio\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void register($shortocde)
 * @method static mix getRegistered()
 * @method static string getItemByKey($key)
 * @method static string compile(string $content)
 * @method static string compileOnly(string $content)
 * @method static string strip(string $content)
 * @method static void disable()
 * @method static void enable()
 *
 * @see \Sokeio\Facades\Shortcode
 */
class Shortcode extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "shortcode";
    }
}
