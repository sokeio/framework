<?php

namespace Sokeio\UI\Field;

use Illuminate\Support\Facades\Blade;

class LivewireField extends FieldUI
{
    public function component($name)
    {
        return $this->vars('component', $name);
    }
    protected function fieldView()
    {
        $component = $this->getVar('component', '', true);
        $attr = $this->getAttr();
        return Blade::render("<livewire:{$component} {$attr} />", []);;
    }
}
