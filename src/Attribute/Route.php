<?php

namespace Sokeio\Attribute;

use Attribute;
use Sokeio\Enums\MethodType;

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
        public array $options = [],
        public bool $isWeb = true,
        public $enableKeyInSetting = null,
        public $enable = true
    ) {}
}
