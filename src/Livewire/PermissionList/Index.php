<?php

namespace Sokeio\Livewire\PermissionList;

use Livewire\Attributes\Modelable;
use Sokeio\Component;
use Sokeio\Models\Permission;

class Index extends Component
{
    #[Modelable]
    public $value = [];
    public function render()
    {
        return view('sokeio::livewire.permission-list.index', [
            'allPermissions' => Permission::query()->get()->groupBy('group'),
        ]);
    }
}
