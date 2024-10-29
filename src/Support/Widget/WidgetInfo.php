<?php

namespace Sokeio\Support\Widget;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class WidgetInfo
{
    public function __construct(
        public $key,
        public $name = '',
        public $icon = null
    ) {}
    public static function getWidgetInfoFromUI($ui): ?self
    {
        // use ReflectionClass
        $reflection = new \ReflectionClass($ui);
        $attributes = $reflection->getAttributes(static::class, \ReflectionAttribute::IS_INSTANCEOF);
        foreach ($attributes as $attribute) {
            return $attribute->newInstance();
        }
        return null;
    }
}
