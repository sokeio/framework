<?php

namespace Sokeio\Pattern;


trait Singleton
{
    private static $instances = [];
    public static function getInstance()
    {
        $class = get_called_class();
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = static::make();
        }
        return self::$instances[$class];
    }
    public static function init()
    {
        return new static();
    }
}
