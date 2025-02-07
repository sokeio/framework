<?php

namespace Sokeio\Attribute;

use Attribute;
use Sokeio\Concerns\WithAttribute;

#[Attribute(Attribute::TARGET_ALL)]
class Permission
{
    use WithAttribute;
    public function __construct(
        private string $slug,
        private string $name,
    ) {}
    public function getKey()
    {
        return $this->slug;
    }
    public function getName()
    {
        return $this->name;
    }
}
