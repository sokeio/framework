<?php

namespace Sokeio;

class ModuleManager
{
    use \Sokeio\Concerns\WithSystemExtend;
    public function getName()
    {
        return "module";
    }
}
