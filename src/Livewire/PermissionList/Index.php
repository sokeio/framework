<?php

namespace Sokeio\Livewire\PermissionList;

use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Modelable;
use Sokeio\Component;

class Index extends Component
{
    #[Modelable]
    public $values = [];
    public function mount()
    {
        if (!is_array($this->values)) {
            $this->values = [];
        }
    }
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
            $children = $this->treePermissions($colPers->where(function ($item) use ($level) {
                return $item['level'] > $level;
            })
                ->groupBy(function ($item) use ($level) {
                    return $item['levels'][$level + 1];
                })->sortKeys(), $level + 1, []);
            $levels =     $colPers->first()['levels'];
            $subKey = '';
            for ($index = 0; $index < $level - 1; $index++) {
                $subKey .= $levels[$index] . '.';
            }

            if (Route::has($subKey)) {
                $children = array_merge([
                    [
                        'key' => $subKey,
                        'info' => [
                            'name' => str($subKey)->replace('-', ' ')->title(),
                        ],
                        'level' => $level,
                    ],
                    $children
                ]);
            }
            $item = [
                'key' => $key,
                'info' => $info,
                'level' => $level,
                'children' =>  $children
            ];
            $data[] = $item;
        }

        return $data;
    }
    public function render()
    {
        $allPermissions = $this->treePermissions(
            config('sokeio.model.permission')::all()
                ->map(function ($permission) {
                    $levels = $permission->slug ? explode('.', $permission->slug) : 0;
                    array_splice($levels, 0, 2);
                    return [
                        'name' =>  $permission->name,
                        'group' => $permission->group,
                        'slug' => $permission->slug,
                        'id' => $permission->id,
                        'levels' => $levels,
                        'level' => count($levels) - 1
                    ];
                })->groupBy(function ($item) {
                    return $item['levels'][0];
                })->sortKeys()
        );
        return view('sokeio::livewire.permission-list.index', [
            'allPermissions' => $allPermissions
        ]);
    }
}
