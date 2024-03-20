<?php

namespace Sokeio\Components\Common\Concerns;

trait WithName
{
    public function name($name)
    {
        return $this->setKeyValue('name', $name);
    }
    public function getName()
    {
        return $this->getValue('name');
    }
    
}
