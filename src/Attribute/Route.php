<?php

namespace Sokeio\Attribute;

use Attribute;
use Exception;
use Illuminate\Support\Facades\Route as FacadesRoute;
use Sokeio\Enums\MethodType;
use Sokeio\Support\Platform\ItemInfo;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class Route
{
    public function __construct(
        public MethodType $method,
        public string $uri,
        public string $name = '',
        public string $label = '',
        public array $middleware = [],
        public array $where = [],
        public array $options = []
    ) {}
    public static function register($class, ItemInfo $item, $fnFilter = null)
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
                $attributes = $reflector->getAttributes(Route::class);
                if (count($attributes) > 0) {
                    foreach ($attributes as $attribute) {
                        $route = $attribute->newInstance();
                        if ($fnFilter && !call_user_func($fnFilter, $route, $method, $class)) {
                            continue;
                        }
                        if (!$route->uri) {
                            throw new Exception("$class@$method uri is not found");
                        }
                        if (!$route->name) {
                            $item->name = $name;
                            if ($method !== 'index') {
                                $item->name =  $item->name . '.' . str($method)->kebab();
                            }
                        }

                        $routeMatch = FacadesRoute::match($route->method->value, $route->uri, [$class, $method]);
                        $routeMatch->name($item->name);
                        if ($route->middleware) {
                            $routeMatch->middleware($route->middleware);
                        }

                        foreach ($route->where as $where) {
                            $routeMatch->where($where[0], $where[1]);
                        }
                    }
                }
            }
        }
    }
}
