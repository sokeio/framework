<?php

namespace Sokeio\Livewire\Permission;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Sokeio\Components\Table;
use Sokeio\Components\UI;
use Sokeio\Models\Permission;

class PermissionTable extends Table
{
    public function getTitle()
    {
        return __('Permission');
    }
    public function LoadPermission()
    {
        Schema::disableForeignKeyConstraints();
        Permission::truncate();
        Schema::enableForeignKeyConstraints();
        $listRoutes = Route::getRoutes()->getRoutes();

        $ignores = applyFilters(PLATFORM_PERMISSION_IGNORE, []);
        foreach ($listRoutes as $item) {
            $name = $item->getName();
            if (
                $name &&
                ($middlewares = $item->gatherMiddleware()) &&
                !str_starts_with($name, '_') &&
                (count($ignores) == 0 || !in_array($name, $ignores))
            ) {
                foreach ($middlewares as $mid) {
                    if (is_a($mid, \Illuminate\Auth\Middleware\Authenticate::class, true)) {
                        Permission::query()->create([
                            'name' => $name, 'group' => $name, 'slug' => $name
                        ]);
                    }
                }
            }
        }
        $customes = applyFilters(PLATFORM_PERMISSION_CUSTOME, []);
        if ($customes && count($customes)) {
            foreach ($customes as $name) {
                if (count($ignores) == 0 || !in_array($name, $ignores)) {
                    Permission::query()->create([
                        'name' => $name, 'group' => $name, 'slug' => $name
                    ]);
                }
            }
        }
        $this->showMessage(__('Update Permission success'));
    }
    protected function getModel(): string
    {
        return Permission::class;
    }
    public function getButtons()
    {
        return [
            UI::button(__('Load Permission'))->className('bg-warning')->wireClick('LoadPermission()')
        ];
    }
    public function getTableActions()
    {
        return [];
    }
    // public function showSearchUI(){
    //     return true;
    // }
    // public function searchUI()
    // {
    //     return [
    //         UI::row([
    //             UI::column6([
    //                 UI::text('name')->LIKE()->label('Tên Role')
    //             ])
    //         ])

    //     ];
    // }

    public function getColumns()
    {
        return [
            UI::text('name')->label(__('Name')),
            UI::text('slug')->label(__('Slug'))
        ];
    }
}
