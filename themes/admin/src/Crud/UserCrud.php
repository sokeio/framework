<?php

namespace ByteTheme\Admin\Crud;

use BytePlatform\Button;
use BytePlatform\CrudManager;
use BytePlatform\Item;
use BytePlatform\ItemManager;
use BytePlatform\Models\Permission;
use BytePlatform\Models\Role;
use BytePlatform\Models\User;

class UserCrud extends CrudManager
{
    public function GetModel()
    {
        return User::class;
    }
    public function GetFields()
    {
        return [
            Item::Add('id')->Title('ID')->DisableFilter()->DisableSort()->When(function ($item, $manager) {
                return $manager->IsTable();
            })->DisableEdit(),
            // Item::Add('password')->Title('Test')->Type('choose-modal')->Required()->When(function ($item, $manager) {
            //     return !$manager->IsTable();
            // })->DataOption(function () {
            //     return [
            //         'modal' => route('admin.user-test1-form'),
            //         'modal-title' => 'Choose Permission',
            //         'modal-choose' => 'Choose Permission'
            //     ];
            // choose-modal.
            // })->DataText(function(){
            //     return '
            //     <div>
            //         <template x-for="item in dataItems">
            //             <span class="badge bg-blue text-blue-fg m-1" x-text="item.name"></span>
            //         </template>
            //     </div>
            //     ';
            // }),

            Item::Add('avatar')->Title('Avatar')->Type('images')->When(function ($item, $manager) {
                return !$manager->IsTable();
            }),
            Item::Add('name')->Title('Name')->Required(),
            Item::Add('email')->Title('Email')->Required(),
            Item::Add('phone_number')
                ->Title('Phone Number')
                ->Rules(['min:6']),
            Item::Add('password')
                ->Title('Password')
                ->Type('password')
                ->When(function ($item, $manager) {
                    return !$manager->IsTable() && !$manager->getData()->getDataId();
                }),
            Item::Add('status')->DataOptionStatus()->Title('status')->DataText(function (Item $item) {
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
    public function TablePage()
    {
        return ItemManager::Table()
            ->Model($this->GetModel())
            ->Title('User Manager')
            // ->EditInTable()
            ->Filter()
            ->Sort()
            ->CheckBoxRow()
            ->Item($this->GetFields())
            ->ButtonOnPage(function () {
                return [
                    Button::Create("Create User")->ButtonType(function () {
                        return 'primary';
                    })->ModalUrl(function ($button) {
                        return route('admin.user-form');
                    })->ModalTitle('Create User')
                ];
            })
            ->ButtonInTable(function () {
                return [
                    Button::Create("Edit")->ButtonType(function () {
                        return 'info';
                    })->ModalUrl(function ($button) {
                        return route('admin.user-form', ['dataId' => $button->getData()->id]);
                    })->ModalTitle('Edit User'),
                    Button::Create("Remove")->ButtonType(function () {
                        return 'warning';
                    })->ConfirmTitle("Remove Role")->Confirm("Sure you wanna delete?")->WireClick(function ($button) {
                        $item = $button->getData();
                        return "callDoAction('deleteRow',{'id':" . $item->id . "})";
                    }),
                    Button::Create("Permission")->ButtonType(function () {
                        return 'warning';
                    })->ModalUrl(function ($button) {
                        return route('admin.user-permission-form', ['dataId' => $button->getData()->id]);
                    })->ModalTitle('Permission User'),
                    Button::Create("Set Password")->ButtonType(function () {
                        return 'warning';
                    })->ModalUrl(function ($button) {
                        return route('admin.user-change-password-form', ['dataId' => $button->getData()->id]);
                    })->ModalTitle('Change Password'),

                    // Button::Create("Save")->ButtonType(function () {
                    //     return 'warning';
                    // })->ConfirmTitle("Save User")->Confirm("Sure you wanna save?")->WireClick(function ($button) {
                    //     $item = $button->getData();
                    //     return "callDoAction('saveRow',{'id':" . $item->id . "})";
                    // })
                ];
            })->ButtonInAction(function () {
                return [
                    // Button::Create("Remove All")->ButtonType(function () {
                    //     return 'primary';
                    // })->ConfirmTitle("Remove All User")->Confirm("Sure you wanna delete?")->WireClick(function ($button) {
                    //     return "callDoAction('testAll',{})";
                    // }),
                    // Button::Create("Export All")->ButtonType(function () {
                    //     return 'primary';
                    // }),
                    // Button::Create("Import")->ButtonType(function () {
                    //     return 'primary';
                    // }),
                ];
            })
            ->Action('changeStatus', function ($params, $compoent) {
                ['id' => $id, 'status' => $status] = $params;
                ($this->GetModel())::where('id', $id)->update(['status' => $status]);
                // $compoent->showMessage(json_encode(['id' => $id, 'status' => $status]));
            })->Action('deleteRow', function ($params, $compoent) {
                ['id' => $id] = $params;
                ($this->GetModel())::where('id', $id)->delete();
                $compoent->showMessage("Delete record successfully.");
            })
            ->Action('testAll', function ($params, $compoent) {
                return $compoent->selectIds;
            })
            // ->FormSearch([
            //     Item::Add('email')->Title('Email')
            // ])
        ;
    }
    public function FormPage()
    {
        return ItemManager::Form()
            ->Model($this->GetModel())
            ->Title('User Form')
            ->Message(function ($manager) {
                if ($manager->getData()->getDataId() > 0) {
                    return 'Update User success';
                }
                return 'Create User success';
            })
            ->Item($this->GetFields());
    }
    public function SetupFormCustom()
    {
        $this->FormCustom('permission', function () {
            return ItemManager::Form()
                ->Model($this->GetModel())->Item([
                    Item::Add('email')->Title('Email')->Column(Item::Col12)->Type('readonly'),
                    Item::Add('RoleIds')->Title('Role')->Column(Item::Col12)->Type('toggle-multiple')->DataOption(function () {
                        return Role::all()->map(function ($item) {
                            return [
                                'value' => $item->id,
                                'text' => $item->name
                            ];
                        });
                    }),
                    Item::Add('PermissionIds')->Title('Permissions')->Column(Item::Col12)->Type('toggle-multiple')->DataOption(function () {
                        return \BytePlatform\Models\Permission::all()->map(function ($item) {
                            return [
                                'value' => $item->id,
                                'text' => $item->name
                            ];
                        });
                    })
                ])->FormDoSave(function ($params, $component, $manager) {
                    $user = User::find($component->dataId);
                    $user->permissions()->sync(collect($component->form->PermissionIds)->filter(function ($item) {
                        return $item > 0;
                    })->toArray());
                    $user->roles()->sync(collect($component->form->RoleIds)->filter(function ($item) {
                        return $item > 0;
                    })->toArray());
                    $component->showMessage("Update permission of user success");
                    $component->closeComponent();
                    $component->refreshRefComponent();
                });
        })->FormCustom('change-password', function () {
            return ItemManager::Form()
                ->NoBindData()
                ->Model($this->GetModel())->Item([
                    Item::Add('password')->Title('Password')->Column(Item::Col12)->Type('password')->Required()->Rules(['same:check_password']),
                    Item::Add('check_password')->Title('RePassword')->Column(Item::Col12)->Type('password')->NoBindData()->Required(),

                ])->ButtonSaveText(function () {
                    return 'Change password';
                })->DataDefaultId(function () {
                    return auth()->user()->id;
                });
        });
    }
}
