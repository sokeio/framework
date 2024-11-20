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
   
}
