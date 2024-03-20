<?php

namespace Sokeio\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void reload()
 * @method static mix optionUI(array $ui = [])
 * @method static void saveOption()
 * @method static array getOptionUI()
 * @method static bool checkOptionUI()
 * @method static mix getValue($key, $default = null)
 * @method static mix setValue($key, $value, $saveNow = true)
 * @see \Sokeio\Facades\ThemeOption
 */
class ThemeOption extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Sokeio\Platform\ThemeOptionManager::class;
    }
}
