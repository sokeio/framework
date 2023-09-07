<?php

use Illuminate\Support\Facades\Route;


Route::group(['as' => 'admin.'], function () {
  //  Route::get('/profile', route_theme(\ByteTheme\Admin\Livewire\Pages\Profile\Index::class))->name('profile');
    Route::get('/profile', route_theme(\ByteTheme\Admin\Livewire\Pages\Profile\Edit::class))->name('profile');
});
