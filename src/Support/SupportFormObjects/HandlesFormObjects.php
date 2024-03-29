<?php

namespace Sokeio\Support\SupportFormObjects;

use Livewire\Drawer\Utils;

trait HandlesFormObjects
{
    public function getFormObjects()
    {
        $forms = [];

        foreach ($this->all() as  $value) {
            if ($value instanceof Form) {
                $forms[] = $value;
            }
        }

        return $forms;
    }
}
