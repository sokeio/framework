<?php

namespace Sokeio;

use Illuminate\Routing\RouteAction;
use Illuminate\Support\Facades\Route;

class Action
{
    private static $_actions = [];
    public static function Register($actions, $namespace, $force = false)
    {
        if (is_array($actions)) {
            foreach ($actions as $key => $item) {
                self::RegisterItem($key, $item, $namespace, $force);
            }
            return;
        }
    }
    public static function RegisterItem($key, $item, $namespace, $force = false)
    {
        $actionKey = $namespace . '::' . $key;
        if (!isset(self::$_actions[$actionKey])) {
            self::$_actions[$actionKey] = $item;
            return;
        }
        if (isset(self::$_actions[$key]) && $force) {
            self::$_actions[$actionKey] = $item;
            return;
        }
    }
    public static function getActions()
    {
        return (self::$_actions);
    }
    public static function hasAction($key)
    {
        return isset(self::$_actions[$key]);
    }
    public static function CallActionWithParams($key, $params)
    {
        if (isset(self::$_actions[$key])) {
            return app(self::$_actions[$key])->handleWithParams($params);
        }
        return null;
    }
    public static function CallAction($key, ...$params)
    {
        if (isset(self::$_actions[$key])) {
            return  app(self::$_actions[$key])(...$params);
        }
        return null;
    }
    public static function CallFromRoute($key, $params = [])
    {
        return function () use ($key, $params) {
            if (isset(self::$_actions[$key])) {
                $route = Route::current();
                foreach ($params as $key => $value) {
                    $route->setParameter($key, $value);
                }
                $route->setAction(array_merge($route->getAction(), RouteAction::parse($route->uri(), ['uses' => self::$_actions[$key] . '@DoAction'])));
                return $route->run();
            }
        };
    }
}
