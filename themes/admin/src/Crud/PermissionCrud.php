<?php

namespace ByteTheme\Admin\Crud;

use BytePlatform\Button;
use BytePlatform\CrudManager;
use BytePlatform\Item;
use BytePlatform\ItemManager;
use BytePlatform\Models\Permission;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

class PermissionCrud extends CrudManager
{
    public function GetModel()
    {
        return Permission::class;
    }
    public function GetFields()
    {
        return [
            Item::Add('id')->Title('ID')->DisableFilter()->DisableSort()->When(function ($item, $manager) {
                return $manager->IsTable();
            })->DisableEdit(),
            Item::Add('name')->Title('Name')->Required(),
            Item::Add('slug')->Title('Slug')->Required(),
        ];
    }
    public function TablePage()
    {
        return ItemManager::Table()
            ->Model($this->GetModel())
            ->Title('Permission Manager')
            // ->EditInTable()
            ->Filter()
            ->Sort()
            ->CheckBoxRow()
            ->Item($this->GetFields())
            ->ButtonOnPage(function () {
                return [
                    Button::Create("Load Permission")->ButtonType(function () {
                        return 'warning';
                    })->ConfirmTitle("Load Permission")->Confirm("Sure you wanna load permssion?")->WireClick(function ($button) {
                        $item = $button->getData();
                        return "callDoAction('LoadPermission')";
                    })
                ];
            })
            ->ButtonInTable(function () {
                return [];
            })->ButtonInAction(function () {
                return [];
            })
            ->Action('LoadPermission', function ($params, $compoent) {
                Schema::disableForeignKeyConstraints();
                Permission::truncate();
                Schema::enableForeignKeyConstraints();
                $listRoutes = Route::getRoutes()->getRoutes();

                $IGNORES = apply_filters(PLATFORM_PERMISSION_IGNORE, []);
                foreach ($listRoutes as $item) {
                    if (($name = $item->getName()) && ($middlewares = $item->gatherMiddleware())) {
                        if (!str_starts_with($name, '_') && (count($IGNORES) == 0 || !in_array($name, $IGNORES))) {
                            foreach ($middlewares as $mid) {
                                if (is_a($mid, \Illuminate\Auth\Middleware\Authenticate::class, true)) {
                                    Permission::query()->create([
                                        'name' => $name, 'group' => $name, 'slug' => $name
                                    ]);
                                }
                            }
                        }
                    }
                }
                $customes = apply_filters(PLATFORM_PERMISSION_CUSTOME, []);
                if ($customes && count($customes)) {
                    foreach ($customes as $name) {
                        if (count($IGNORES) == 0 || !in_array($name, $IGNORES))
                            Permission::query()->create([
                                'name' => $name, 'group' => $name, 'slug' => $name
                            ]);
                    }
                }
                $compoent->showMessage('Update Permission success');
            });
    }
    public function FormPage()
    {
    }
    public function SetupFormCustom()
    {
    }
}
