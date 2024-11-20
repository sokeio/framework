<?php

namespace Sokeio\Livewire\PermissionList;

use Livewire\Attributes\Lazy;
use Livewire\Attributes\Modelable;
use Sokeio\Component;
use Sokeio\Models\Permission;

class Index extends Component
{
    #[Modelable]
    public $values = [];
    private function treePermissions($permissions, $level = 0)
    {
        $data = collect([]);
        foreach ($permissions as $key => $permission) {
            $colPers = collect($permission);
            $info =  $colPers->where('level', $level)->first();
            if ($info == null) {
                $info = [
                    'name' => str($key)->replace('-', ' ')->title(),
                ];
            }
            $group = [
                'key' => $key,
                'info' => $info,
                'level' => $level,
                'children' => $this->treePermissions($colPers->where('level', '>', $level)
                    ->groupBy(function ($item) use ($level) {
                        return $item['levels'][$level];
                    }), $level + 1, [])
            ];
            $data[] = $group;
        }

        return $data;
    }
    public function render()
    {
        return view('sokeio::livewire.permission-list.index', [
            'allPermissions' => $this->treePermissions(Permission::all()->map(function ($permission) {
                $levels = explode('.', explode('-page.', $permission->slug)[1]);
                return [
                    'name' => $levels[count($levels) - 1],
                    'group' => $permission->group,
                    'slug' => $permission->slug,
                    'id' => $permission->id,
                    'levels' => $levels,
                    'level' => count($levels)
                ];
            })->groupBy('group'))
        ]);
    }
}
