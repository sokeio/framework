<?php

namespace Sokeio\Facades;

use Illuminate\Support\Facades\Facade;
use Sokeio\Menu\MenuBuilder;

/**
 * 
 * @method static string render($_position = '')
 * @method static string getDefault()
 * @method static void switchDefault($default)
 * @method static MenuBuilder position($_position = '')
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
 * @method static MenuBuilder withDatabase($data, $_position = '')
 * @method static void DoRegister()
 *
 * @see \Sokeio\Menu\MenuManager
 */
class Menu extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Sokeio\Menu\MenuManager::class;
    }
}
