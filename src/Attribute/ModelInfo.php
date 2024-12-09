<?php

namespace Sokeio\Attribute;

use Attribute;
use Sokeio\Concerns\WithAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ModelInfo
{
    use WithAttribute;
    public function __construct(
        public $value = 'id',
        public $text = 'name',
        public $fillable = null,
        public $searchable = null,
        public $createBy = 'created_by',
        public $updateBy = 'updated_by',
        public $skip = false
    ) {}
}