<?php

namespace Sokeio\Concerns;

use Sokeio\Facades\Assets;
use Sokeio\Facades\Theme;

trait WithModelAssets
{
    public function setAssetLayout()
    {
        if ($this->layout) {
            Theme::setLayout($this->layout);
        }
        if ($this->css) {
            Assets::AddStyle($this->css);
        }
        if ($this->custom_css) {
            Assets::AddStyle($this->custom_css);
        }
        if ($this->js) {
            Assets::AddScript($this->js);
        }
        if ($this->custom_js) {
            Assets::AddScript($this->custom_js);
        }
    }
    public function setAssets()
    {
        $this->setAssetLayout();
        Assets::setTitle($this->name);
        Assets::setDescription($this->description);
        Assets::setKeywords($this->keywords);
        if (function_exists('SeoHelper')) {
            SeoHelper()->for($this);
        }
        if (breadcrumb()->exists()) {
            breadcrumb()->title($this->name);
        }
        return $this;
    }
}
