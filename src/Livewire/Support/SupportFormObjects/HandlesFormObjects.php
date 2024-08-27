<?php

namespace Sokeio\Livewire\Support\SupportFormObjects;


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
