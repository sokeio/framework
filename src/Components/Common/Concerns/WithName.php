<?php

namespace Sokeio\Components\Common\Concerns;

trait WithName
{
    public function name($name): static
    {
        return $this->setKeyValue('name', $name);
    }
    public function getName()
    {
        return $this->getValue('name');
    }
    
}
