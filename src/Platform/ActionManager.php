<?php

namespace Sokeio\Platform;

use Illuminate\Routing\RouteAction;
use Illuminate\Support\Facades\Route;

class ActionManager
{
    private $actions = [];
    public function register($actions, $namespace, $force = false)
    {
        if (is_array($actions)) {
            foreach ($actions as $key => $item) {
                $this->registerItem($key, $item, $namespace, $force);
            }
        }
    }
    public function registerItem($key, $item, $namespace, $force = false)
    {
        $actionKey = $namespace . '::' . $key;
        if (!isset($this->actions[$actionKey])) {
            $this->actions[$actionKey] = $item;
            return;
        }
        if (isset($this->actions[$key]) && $force) {
            $this->actions[$actionKey] = $item;
        }
    }
    public function getActions()
    {
        return $this->actions;
    }
    public function hasAction($key)
    {
        return isset($this->actions[$key]);
    }
    public function actionWithParams($key, $params)
    {
        if (isset($this->actions[$key])) {
            return app($this->actions[$key])->handleWithParams($params);
        }
        return null;
    }
    public function action($key, ...$params)
    {
        if (isset($this->actions[$key])) {
            return  app($this->actions[$key])(...$params);
        }
        return null;
    }
    public function actionFromRoute($key, $params = [])
    {
        return function () use ($key, $params) {
            if (isset($this->actions[$key])) {
                $route = Route::current();
                foreach ($params as $key => $value) {
                    $route->setParameter($key, $value);
                }
                $route->setAction(array_merge($route->getAction(), RouteAction::parse($route->uri(), [
                    'uses' => $this->actions[$key] . '@doAction'
                ])));
                return $route->run();
            }
        };
    }
}
