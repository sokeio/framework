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
        public $sort = 99999,
        public $icon = null,
        public $admin = null,
        public $auth = null,
        public $menu = null,
        public $menuTitle = null,
        public $menuClass = null,
        public $menuIcon = null,
        public $menuTarget = null,
        public $menuTargetTitle = null,
        public $menuTargetIcon = null,
        public $menuTargetId = null,
        public $menuTargetClass = null,
        public $menuTargetSort = null,
        public $skipHtmlAjax = null,
        public $model = null,
        public $urlKeyInSetting = null,
        public $enableKeyInSetting = null,
        public $enable = null
    ) {}
}
