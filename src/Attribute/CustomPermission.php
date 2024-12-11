<?php

namespace Sokeio\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_ALL)]
class CustomPermission
{
    public function __construct(
        public string $name,
        public string $slug,
        public string $group
    ) {}
}
