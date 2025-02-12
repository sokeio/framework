<?php

namespace Sokeio\Core\Loader;

use Closure;
use Exception;
use Illuminate\Support\Facades\Route as FacadesRoute;
use Sokeio\Core\Attribute\RouteInfo;
use Sokeio\Platform;
use Sokeio\Core\IPipeLoader;
use Sokeio\Core\ItemInfo;

class ControllerLoader implements IPipeLoader
{
    const KEY = 'Controller';
    public function handle(ItemInfo $item, Closure $next): mixed
    {
        Platform::scanAllClass(
            $item->getPackage()->basePath('Http/Controllers'),
            $item->namespace . '\\Http\\Controllers',
            function ($class) use ($item) {
                if (!str($class)->endsWith(self::KEY)) {
                    return;
                }
                $this->registerRoute($class, $item);
            }
        );
        return $next($item);
    }
    private function registerRoute($class, ItemInfo $item, $fnFilter = null)
    {
        // get All methods with attribute Route in class
        if (!is_subclass_of($class, \Illuminate\Routing\Controller::class)) {
            return;
        }

        $methods = get_class_methods($class);
        // split Http\\Controllers
        $name = explode('Http\\Controllers\\', $class)[1];
        $name = $item->getPackage()->shortName() . '.' . str($name)
            ->chopEnd('Controller')
            ->explode('\\')
            ->map(fn($item) => str($item)->kebab())
            ->join('.');
        foreach ($methods as $method) {
            $reflector = new \ReflectionMethod($class, $method);
            if ($reflector->isPublic()) {
                $attributes = $reflector->getAttributes(RouteInfo::class);
                if (count($attributes) > 0) {
                    foreach ($attributes as $attribute) {
                        $route = $attribute->newInstance();
                        if ($fnFilter && !call_user_func($fnFilter, $route, $method, $class)) {
                            continue;
                        }
                        if ($route->enableKeyInSetting && !setting($route->enableKeyInSetting, $route->enable)) {
                            continue;
                        }
                        if (!$route->uri) {
                            throw new Exception("$class@$method uri is not found");
                        }
                        if (!$route->name) {
                            $route->name = $name;
                            if ($method !== 'index') {
                                $route->name =  $route->name . '.' . str($method)->kebab();
                            }
                        }


                        if (!$route->middleware) {
                            $route->middleware = [];
                        }
                        if (!is_array($route->middleware)) {
                            $route->middleware = [$route->middleware];
                        }

                        if ($route->isWeb) {
                            Platform::routeWeb(function () use ($class, $method, $route) {
                                $routeMatch = FacadesRoute::match($route->method->value, $route->uri, [$class, $method]);
                                $routeMatch->name($route->name);
                                if ($route->middleware) {
                                    $routeMatch->middleware($route->middleware);
                                }

                                foreach ($route->where as $where) {
                                    $routeMatch->where($where[0], $where[1]);
                                }
                            }, $route->isAuth);
                        } else {
                            Platform::routeApi(function () use ($class, $method, $route) {
                                $routeMatch = FacadesRoute::match($route->method->value, $route->uri, [$class, $method]);
                                $routeMatch->name($route->name);
                                if ($route->middleware) {
                                    $routeMatch->middleware($route->middleware);
                                }

                                foreach ($route->where as $where) {
                                    $routeMatch->where($where[0], $where[1]);
                                }
                            }, !$route->isAuth);
                        }
                    }
                }
            }
        }
    }
}
