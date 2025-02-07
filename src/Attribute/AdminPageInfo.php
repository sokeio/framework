<?php

namespace Sokeio\Attribute;

use Attribute;
use Sokeio\Concerns\WithAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
class AdminPageInfo extends PageInfo
{
    use WithAttribute;
    public function __construct(
        $title = null,
        $layout = null,
        $route = null,
        $url = null,
        $auth = null,
        $skipHtmlAjax = null,
        $urlKeyInSetting = null,
        $enableKeyInSetting = null,
        $enable = null,
        public $skipPermision = null,
        public $admin = null,
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
        public $sort = 99999,
        public $icon = null,
        public $model = null
    ) {
        parent::__construct(
            $title,
            $layout,
            $route,
            $url,
            $auth,
            $skipHtmlAjax,
            $urlKeyInSetting,
            $enableKeyInSetting,
            $enable,
        );
    }
}
