<?php

namespace Sokeio;

use Illuminate\Support\Facades\Facade;
use Sokeio\Core\SettingManager;

/**
 * @see \Sokeio\Setting
 *
 * @method static mixed get($key, $default = null)
 * @method static void set($key, $value)
 * @method static SettingManager save()
 * @method static SettingManager clear()
 * @method static SettingManager load()
 * @method static SettingManager ui($key, $ui)
 * @method static array getUI($key = null)
 *
 */
class Setting extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SettingManager::class;
    }
}
