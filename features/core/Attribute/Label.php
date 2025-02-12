<?php

namespace Sokeio\Core\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class Label
{
    public function __construct(public string $label) {}
    public function get()
    {
        return $this->label;
    }
}
