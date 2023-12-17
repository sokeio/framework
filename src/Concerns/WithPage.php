<?php

namespace Sokeio\Concerns;

use Livewire\Attributes\Computed;

trait WithPage
{
    #[Computed]
    public function PageTitle()
    {
        if (method_exists($this, 'getTitle'))
            return $this->getTitle();
        return "";
    }
    #[Computed]
    public function Breadcrumb()
    {
        if (method_exists($this, 'getBreadcrumb'))
            return $this->getBreadcrumb();
        return [];
    }
}
