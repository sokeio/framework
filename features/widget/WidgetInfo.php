<?php

namespace Sokeio\Widget;

use Attribute;
use Sokeio\Concerns\WithAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
class WidgetInfo
{
    use WithAttribute;
    public function __construct(
        public $key,
        public $name = '',
        public $icon = null,
        public $description = '',
        public $useDefault = true,
    ) {}
}
