<?php

namespace Sokeio\Core\Attribute;

use Attribute;
use Sokeio\Core\Concerns\WithAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
class AdminPageInfo extends PageInfo
{
    use WithAttribute;
    public function __construct(
        public $title = null,
        public $layout = null,
        public $route = null,
        public $url = null,
        public $auth = true,
        public $skipHtmlAjax = null,
        public $urlKeyInSetting = null,
        public $enableKeyInSetting = null,
        public $enable = null,
        public $skipPermision = null,
        public $admin = true,
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
