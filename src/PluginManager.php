<?php

namespace BytePlatform;


class PluginManager
{
    use \BytePlatform\Concerns\WithSystemExtend;
    public function getName()
    {
        return "plugin";
    }
}
