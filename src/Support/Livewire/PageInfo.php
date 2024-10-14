<?php

namespace Sokeio\Support\Livewire;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class PageInfo
{
    public function __construct(
        public $title = null,
        public $layout = 'none',
        public $url = null,
        public $icon = null,
        public $target = null,
        public $admin = null,
        public $auth = null,
        public $menu = null,
        public $menuTitle = null,
        public $menuTarget = null,
        public $route = null,
        public $skipHtmlAjax = null
    ) {}
}
