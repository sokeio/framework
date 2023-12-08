<?php

namespace Livewire\Features\SupportFormObjects;

use Livewire\Drawer\Utils;
use Sokeio\Form;

trait HandlesFormObjects
{
    public function getFormObjects()
    {
        $forms = [];

        foreach ($this->all() as $key => $value) {
            if ($value instanceof Form) {
                $forms[] = $value;
            }
        }

        return $forms;
    }
}
