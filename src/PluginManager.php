<?php

namespace Sokeio;


class PluginManager
{
    use \Sokeio\Concerns\WithSystemExtend;
    public function getName()
    {
        return "plugin";
    }
}
