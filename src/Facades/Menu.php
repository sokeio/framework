<?php

namespace BytePlatform\Facades;

use Illuminate\Support\Facades\Facade;
use BytePlatform\Builders\Menu\MenuBuilder;

/**
 * 
 * @method static string render($_position = '')
 * @method static string getDefault()
 * @method static void switchDefault($default)
 * @method static MenuBuilder Position($_position = '')
 * @method static MenuBuilder link($link, $text, $icon = '', $attributes = [], $per = '', $sort = -1, $_position = '')
 * @method static MenuBuilder route($data, $text, $icon = '', $attributes = [], $per = '', $sort = -1, $_position = '')
 * @method static MenuBuilder component($data, $text, $icon = '', $attributes = [], $per = '', $sort = -1, $_position = '')
 * @method static MenuBuilder action($data, $text, $icon = '', $attributes = [], $per = '', $sort = -1, $_position = '')
 * @method static MenuBuilder div($text = '', $icon = '', $attributes = [], $per = '', $sort  = -1, $_position = '')
 * @method static MenuBuilder tag($tag, $text, $icon = '', $attributes = [], $per = '', $sort  = -1, $_position = '')
 * @method static MenuBuilder button($text, $icon = '', $attributes = [], $per = '', $sort  = -1, $_position = '')
 * @method static MenuBuilder subMenu($text, $icon = '', $callback, $sort  = -1, $_position = '')
 * @method static MenuBuilder attachMenu($targetId, $callback, $_position = '')
 * @method static MenuBuilder wrapDiv($class, $id, $attributes = [], $_position = '')
 * @method static void renderCallback($callback, $_position = '')
 * @method static void renderItemCallback($callback, $_position = '')
 * @method static void DoRender($item, $_position = '')
 * @method static void DoRenderItem($item, $_position = '')
 * @method static void Register($callback)
 * @method static void DoRegister()
 *
 * @see \BytePlatform\Menu\MenuManager
 */
class Menu extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \BytePlatform\Builders\Menu\MenuManager::class;
    }
}