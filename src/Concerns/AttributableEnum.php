<?php

namespace Sokeio\Concerns;

use BadMethodCallException;
use Illuminate\Support\Str;
use ReflectionAttribute;
use ReflectionEnum;

trait AttributableEnum
{
    /**
     * Call the given method on the enum case
     *
     */
    public function __call(string $method, array $arguments): mixed
    {
        // Get attributes of the enum case with reflection API
        $reflection = new ReflectionEnum(static::class);
        $attributes = $reflection->getCase($this->name)->getAttributes();
        // Check if attribute exists in our attributes list
        $filtered_attributes = array_filter($attributes, function (ReflectionAttribute $attribute) use ($method) {
            return class_basename($attribute->getName()) === Str::ucfirst($method);
        });

        // If not, throw an exception
        if (empty($filtered_attributes)) {
            if (empty($arguments)) {
                throw new BadMethodCallException(sprintf('Call to undefined method %s::%s()', static::class, $method));
            }
            return $arguments[0];
        }

        return array_shift($filtered_attributes)->newInstance()->get();
    }
}
