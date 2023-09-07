<?php

namespace ByteTheme\Admin\Crud;

use BytePlatform\Button;
use BytePlatform\CrudManager;
use BytePlatform\Item;
use BytePlatform\ItemManager;
use BytePlatform\Models\Role;

class RoleCrud extends CrudManager
{
    public function GetFields()
    {
        return [
            Item::Add('id')->Title('ID')->DisableFilter()->DisableSort()->When(function ($item, $manager) {
                return $manager->IsTable();
            })->DisableEdit(),
            Item::Add('name')->Title('Name')->Required(),
            Item::Add('slug')->Title('Slug')->Required(),
            Item::Add('status')->Title('status')->DataOptionStatus()->DataText(function (Item $item) {
                $button = $item->ConvertToButton()
                    ->Title(function ($button) {
                        $item = $button->getData();
                        return $item->status ? 'Active' : 'Block';
                    })->ButtonType(function ($button) {
                        $item = $button->getData();
                        return $item->status ? 'success' : 'danger';
                    })->When(function ($button) {
                        $data = $button->getData();
                        return $data == null || $data->isSuperAdmin() == false;
                    });
                if ($button->getWhen()) {
                    $button->WireClick(function ($button) {
                        $item = $button->getData();
                        return "callDoAction('changeStatus',{'id':" . $item->id . ",'status':" . ($item->status == 1 ? 0 : 1) . "})";
                    });
                }
                return $button->render();
            })->DisableEdit(function ($item, $manager) {
                return !$manager->IsTable();
            }),
        ];
    }
    public function GetModel()
    {
        return Role::class;
    }
    public function TablePage()
    {
        return ItemManager::Table()
            ->Model($this->GetModel())
            // ->EditInTable()
            ->Title('Role Manager')
            ->Filter()
            ->Sort()
            ->CheckBoxRow()
            ->ButtonOnPage(function () {
                return [
                    Button::Create("Create Role")->ButtonType(function () {
                        return 'primary';
                    })->ModalUrl(function ($button) {
                        return route('admin.role-form');
                    })->ModalTitle('Create Role')
                ];
            })
            ->ButtonInTable(function () {
                return [
                    Button::Create("Edit")->ButtonType(function () {
                        return 'info';
                    })->ModalUrl(function ($button) {
                        return route('admin.role-form', ['dataId' => $button->getData()->id]);
                    })->ModalTitle('Edit Role'),
                    Button::Create("Permission")->ButtonType(function () {
                        return 'warning';
                    })->ModalUrl(function ($button) {
                        return route('admin.role-permission-form', ['dataId' => $button->getData()->id]);
                    })->ModalTitle('Permission Role'),
                    Button::Create("Remove")->ButtonType(function () {
                        return 'warning';
                    })->ConfirmTitle("Remove Role")->Confirm("Sure you wanna delete?")->WireClick(function ($button) {
                        $item = $button->getData();
                        return "callDoAction('deleteRow',{'id':" . $item->id . "})";
                    }),
                ];
            })
            ->Item($this->GetFields())
            ->Action('changeStatus', function ($params, $compoent) {
                ['id' => $id, 'status' => $status] = $params;
                ($this->GetModel())::where('id', $id)->update(['status' => $status]);
                // $compoent->showMessage(json_encode(['id' => $id, 'status' => $status]));
            })->Action('deleteRow', function ($params, $compoent) {
                ['id' => $id] = $params;
                ($this->GetModel())::where('id', $id)->delete();
                $compoent->showMessage("Delete record successfully.");
            });
    }
    public function FormPage()
    {
        return ItemManager::Form()
            ->Model($this->GetModel())
            ->Title('Role Form')
            ->Message(function ($manager) {
                if ($manager->getData()->getDataId() > 0) {
                    return 'Update Role success';
                }
                return 'Create Role success';
            })
            ->Item($this->GetFields());
    }
    public function SetupFormCustom()
    {
        $this->FormCustom('permission', function () {
            return ItemManager::Form()
                ->Model($this->GetModel())->Item([
                    Item::Add('name')->Title('Name')->Column(Item::Col12)->Type('readonly'),
                    Item::Add('selectIds')->Title('Name')->InputHidden()->Attribute(' wire:get-value="selectIds" '),
                    Item::Add('PermissionIds')->Title('Permissions')->Column(Item::Col12)->Type('toggle-multiple')->DataOption(function () {
                        return \BytePlatform\Models\Permission::all()->map(function ($item) {
                            return [
                                'value' => $item->id,
                                'text' => $item->name
                            ];
                        });
                    })
                ])->FormDoSave(function ($params, $component, $manager) {
                    // $component->showMessage(json_encode($component->form->selectIds));
                    $role = Role::find($component->dataId);
                    $role->permissions()->sync(collect($component->form->PermissionIds)->filter(function ($item) {
                        return $item > 0;
                    })->toArray());
                    $component->showMessage("Update permission of role success");
                    $component->closeComponent();
                    $component->refreshRefComponent();
                });
        });
    }
}
