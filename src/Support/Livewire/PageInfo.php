<?php

namespace Sokeio\Support\Livewire;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class PageInfo
{
    public function __construct(
        public $title = null,
        public $layout = 'default',
        public $route = null,
        public $url = null,
        public $icon = null,
        public $target = null,
        public $admin = null,
        public $auth = null,
        public $menu = null,
        public $menuTitle = null,
        public $menuIcon = null,
        public $menuTargetTitle = null,
        public $menuTargetIcon = null,
        public $menuTargetId = null,
        public $menuTargetClass = null,
        public $skipHtmlAjax = null
    ) {}
}
