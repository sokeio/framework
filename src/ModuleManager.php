<?php

namespace BytePlatform;

class ModuleManager
{
    use \BytePlatform\Concerns\WithSystemExtend;
    public function getName()
    {
        return "module";
    }
}
