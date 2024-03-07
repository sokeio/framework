<?php

namespace Sokeio\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void Register($actions, $namespace, $force = false)
 * @method static void RegisterItem($key, $item, $namespace, $force = false)
 * @method static mix getActions()
 * @method static bool hasAction($key)
 * @method static mix ActionWithParams($key, $params)
 * @method static mix Action($key, ...$params)
 * @method static mix ActionFromRoute($key, $params = [])
 *
 * @see \Sokeio\Facades\Action
 */
class Action extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Sokeio\Platform\ActionManager::class;
    }
}
