<?php

namespace BytePlatform\Support\Core;


class PluginManager
{
    use \BytePlatform\Concerns\WithSystemExtend;
    public function getName()
    {
        return "plugin";
    }
}
