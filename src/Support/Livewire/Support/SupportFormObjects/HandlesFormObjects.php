<?php

namespace Sokeio\Support\Livewire\Support\SupportFormObjects;


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
