<?php

namespace Sokeio\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void Register($actions, $namespace, $force = false)
 * @method static void RegisterItem($key, $item, $namespace, $force = false)
 * @method static mix getActions()
 * @method static bool hasAction($key)
 * @method static mix CallActionWithParams($key, $params)
 * @method static mix CallAction($key, ...$params)
 * @method static mix CallFromRoute($key, $params = [])
 * 
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
