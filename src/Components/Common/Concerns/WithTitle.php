<?php

namespace Sokeio\Components\Common\Concerns;

trait WithTitle
{
    public function title($title):static
    {
        return $this->setKeyValue('title', $title);
    }
    public function getTitle()
    {
        return $this->getValue('title');
    }
    
}
