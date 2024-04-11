<?php

namespace Sokeio\Components\Concerns;

use Sokeio\Breadcrumb;
use Sokeio\Facades\Assets;

trait WithBreadcrumb
{
    protected function getTitle()
    {
        return null;
    }
    protected function getBreadcrumb()
    {
        if (sokeioIsAdmin()) {
            return [
                Breadcrumb::Item(__('Home'), route('admin.dashboard'))
            ];
        }
        return [
            Breadcrumb::Item(__('Home'), url(''))
        ];
    }
    protected function doBreadcrumb()
    {
        Assets::setTitle($this->getTitle());
        breadcrumb()->title($this->getTitle())->breadcrumb($this->getBreadcrumb());
    }
}
