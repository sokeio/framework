<?php

namespace Sokeio\Support\Widget;

use Attribute;
use Sokeio\Concerns\WithAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
class WidgetInfo
{
    use WithAttribute;
    public function __construct(
        public $key,
        public $name = '',
        public $icon = null
    ) {}
}
