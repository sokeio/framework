<?php

namespace Sokeio\Concerns;

use Sokeio\Pattern\Tap;

trait WithAttribute
{
    use Tap;
    private $className = null;
    public function getClassName(): string
    {
        return $this->className;
    }

    public static function fromClass($className): ?self
    {
        if (is_object($className)) {
            $className = get_class($className);
        }
        // use ReflectionClass
        $reflection = new \ReflectionClass($className);
        $attributes = $reflection->getAttributes(static::class, \ReflectionAttribute::IS_INSTANCEOF);
        foreach ($attributes as $attribute) {
            return $attribute->newInstance()->tap(fn(self $info) => $info->className = $className);
        }
        return null;
    }
    public static function fromMethod($className)
    {
        if (is_object($className)) {
            $className = get_class($className);
        }
        $classAttributes = [];
        // use ReflectionClass
        $reflection = new \ReflectionClass($className);
        foreach ($reflection->getMethods() as $method) {
            $attributes = $method->getAttributes(static::class, \ReflectionAttribute::IS_INSTANCEOF);
            if (!empty($attributes)) {
                $classAttributes[] = [
                    'className' => $className,
                    'methodName' => $method->getName(),
                    'arguments' => $method->getParameters(),
                    'returnType' => $method->getReturnType(),
                    'attributes' => $attributes
                ];
            }
        }
        return $classAttributes;
    }
}
