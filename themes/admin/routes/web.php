<?php

use Illuminate\Support\Facades\Route;
use SokeioTheme\Admin\Livewire\Auth\ForgotPassword;
use SokeioTheme\Admin\Livewire\Auth\Login;
use SokeioTheme\Admin\Livewire\Auth\Signup;

Route::prefix(adminUrl())->middleware(\Sokeio\Middleware\ThemeAdmin::class)->group(function () {
    Route::name('admin.')->prefix('auth')->middleware('themelayout:none')->group(function () {
        Route::get('login', routeTheme(Login::class))->name('login');
        Route::get('logout', routeTheme(function () {
            auth()->logout();
            return redirect(route('admin.login'));
        }))->name('logout');
        Route::get('sign-up', routeTheme(Signup::class))->name('sign-up');
        Route::get('forgot-password', routeTheme(ForgotPassword::class))->name('forgot-password');
    });
});
