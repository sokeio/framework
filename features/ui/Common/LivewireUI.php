<?php

namespace Sokeio\UI\Common;

use Illuminate\Support\Facades\Blade;
use Sokeio\UI\BaseUI;

class LivewireUI extends BaseUI
{
    public function component($name)
    {
        return $this->vars('component', $name);
    }
    public function lazy($key = null)
    {
        return $this->attr('lazy', $key);
    }
    public function view()
    {
        $component = $this->getVar('component', '', true);
        $attr = $this->getAttr();
        return Blade::render("<livewire:{$component} {$attr} />", []);
    }
}
