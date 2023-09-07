<?php

use Illuminate\Support\Facades\Route;


Route::group(['as' => 'admin.'], function () {
    Route::get('/plugins', route_theme(\BytePlatform\Livewire\Extentions\Index::class, ['ExtentionType' => 'plugin']))->name('plugin');
    Route::get('/modules', route_theme(\BytePlatform\Livewire\Extentions\Index::class, ['ExtentionType' => 'module']))->name('module');
    Route::get('/themes', route_theme(\BytePlatform\Livewire\Extentions\Index::class, ['ExtentionType' => 'theme']))->name('theme');
    Route::get('/theme/options', route_theme(\ByteTheme\Admin\Livewire\Pages\ThemeOptions\Index::class))->name('theme-options');
    Route::post('extention/{ExtentionType}', route_theme(\BytePlatform\Livewire\Extentions\Create::class))->name('create-extention');
    Route::post('extention-file/{ExtentionType}/{ExtentionId}', route_theme(\BytePlatform\Livewire\Extentions\CreateFile::class))->name('create-extention-file');
});
