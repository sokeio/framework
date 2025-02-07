<?php

namespace Sokeio\Attribute;

use Attribute;
use Sokeio\Concerns\WithAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
class PageInfo
{
    use WithAttribute;
    public function __construct(
        public $title = null,
        public $layout = null,
        public $route = null,
        public $url = null,
        public $auth = false,
        public $skipHtmlAjax = null,
        public $urlKeyInSetting = null,
        public $enableKeyInSetting = null,
        public $enable = null
    ) {}
}
