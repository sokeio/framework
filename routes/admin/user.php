<?php

use Illuminate\Support\Facades\Route;
use Sokeio\Livewire\Permission\PermissionTable;
use Sokeio\Livewire\Role\RoleForm;
use Sokeio\Livewire\Role\RoleTable;
use Sokeio\Livewire\User\UserChangePasswordForm;
use Sokeio\Livewire\User\UserForm;
use Sokeio\Livewire\User\UserTable;

Route::group(['as' => 'admin.system.'], function () {
    Route::get('permission', PermissionTable::class)->name('permission');
    Route::post('permission', PermissionTable::class)->name('permission.choose');
    Route::post('user/{dataId}/change-password', UserChangePasswordForm::class)->name('user.change-password');
    routeCrud('role', RoleTable::class, RoleForm::class);
    routeCrud('user', UserTable::class, UserForm::class);
    // routeCrud('language', LanguageTable::class, LanguageForm::class);
});
