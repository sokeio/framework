<?php

namespace Sokeio\Platform;

use Illuminate\Routing\RouteAction;
use Illuminate\Support\Facades\Route;

class ActionManager
{
    private $_actions = [];
    public function Register($actions, $namespace, $force = false)
    {
        if (is_array($actions)) {
            foreach ($actions as $key => $item) {
                $this->RegisterItem($key, $item, $namespace, $force);
            }
            return;
        }
    }
    public function RegisterItem($key, $item, $namespace, $force = false)
    {
        $actionKey = $namespace . '::' . $key;
        if (!isset($this->$_actions[$actionKey])) {
            $this->$_actions[$actionKey] = $item;
            return;
        }
        if (isset($this->$_actions[$key]) && $force) {
            $this->$_actions[$actionKey] = $item;
            return;
        }
    }
    public function getActions()
    {
        return ($this->$_actions);
    }
    public function hasAction($key)
    {
        return isset($this->$_actions[$key]);
    }
    public function CallActionWithParams($key, $params)
    {
        if (isset($this->$_actions[$key])) {
            return app($this->$_actions[$key])->handleWithParams($params);
        }
        return null;
    }
    public function CallAction($key, ...$params)
    {
        if (isset($this->$_actions[$key])) {
            return  app($this->$_actions[$key])(...$params);
        }
        return null;
    }
    public function CallFromRoute($key, $params = [])
    {
        return function () use ($key, $params) {
            if (isset($this->$_actions[$key])) {
                $route = Route::current();
                foreach ($params as $key => $value) {
                    $route->setParameter($key, $value);
                }
                $route->setAction(array_merge($route->getAction(), RouteAction::parse($route->uri(), ['uses' => $this->$_actions[$key] . '@DoAction'])));
                return $route->run();
            }
        };
    }
}
