<?php

namespace Sokeio\Platform;

class PluginManager
{
    use \Sokeio\Concerns\WithSystemExtend;
    public function getName()
    {
        return "plugin";
    }
}
