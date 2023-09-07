<?php

use ByteTheme\Admin\Livewire\Auth\ForgotPassword;
use ByteTheme\Admin\Livewire\Auth\Login;
use ByteTheme\Admin\Livewire\Auth\Signup;
use Illuminate\Support\Facades\Route;

Route::prefix(adminUrl())->middleware(\BytePlatform\Middleware\ThemeAdmin::class)->group(function () {
    Route::name('admin.')->prefix('auth')->middleware('themelayout:none')->group(function () {
        Route::get('login', route_theme(Login::class))->name('login');
        Route::get('logout', route_theme(function () {
            auth()->logout();
            return redirect(route('admin.login'));
        }))->name('logout');
        Route::get('sign-up', route_theme(Signup::class))->name('sign-up');
        Route::get('forgot-password', route_theme(ForgotPassword::class))->name('forgot-password');
    });
});
