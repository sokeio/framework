<?php

namespace Sokeio\Concerns;

trait WithAttribute
{
    private $className = null;
    public function getClassName(): string
    {
        return $this->className;
    }
    public function tap(callable $callback)
    {
        $callback($this);
        return $this;
    }
    public static function getInfoFrom($className): ?self
    {
        // use ReflectionClass
        $reflection = new \ReflectionClass($className);
        $attributes = $reflection->getAttributes(static::class, \ReflectionAttribute::IS_INSTANCEOF);
        foreach ($attributes as $attribute) {
            return $attribute->newInstance()->tap(fn(self $info) => $info->className = $className);
        }
        return null;
    }
}
