<?php

use ByteTheme\Admin\Crud\PermissionCrud;
use ByteTheme\Admin\Crud\RoleCrud;
use ByteTheme\Admin\Crud\UserCrud;
use Illuminate\Support\Facades\Route;


Route::group(['as' => 'admin.'], function () {
    UserCrud::RoutePage('user');
    RoleCrud::RoutePage('role');
    PermissionCrud::RoutePage('permission', false);
});
