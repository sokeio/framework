<?php

namespace Sokeio\Components\Concerns;


trait WithCollapse
{
    public function collapse($collapse = true): static
    {
        return $this->setKeyValue('collapse', $collapse);
    }
    public function expanded()
    {
        return $this->collapse(false);
    }
    public function getCollapse()
    {
        return $this->getValue('collapse');
    }
}
