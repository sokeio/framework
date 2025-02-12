<?php

namespace Sokeio\Core\Attribute;

use Attribute;
use Sokeio\Core\Concerns\WithAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ModelInfo
{
    use WithAttribute;
    public function __construct(
        public $value = 'id',
        public $text = 'name',
        public $fillable = null,
        public $searchable = null,
        public $skipBy = false,
    ) {}
}
