<?php

namespace Sokeio\Pattern;


trait Singleton
{
    private static $instances = [];
    public static function getInstance($key = ''): static
    {
        $class = get_called_class() . $key;
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = static::init();
        }
        return self::$instances[$class];
    }
    public static function init(): static
    {
        return new static();
    }
}
