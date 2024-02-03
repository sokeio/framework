<?php

use Illuminate\Support\Facades\Route;
use Sokeio\Livewire\ColorSetting;
use Sokeio\Livewire\IconSetting;
use Sokeio\Livewire\Menu\MenuForm;
use Sokeio\Livewire\ThemeOptionSetting;
use Sokeio\Livewire\Menu\MenuManager;

Route::group(['as' => 'admin.'], function () {
    Route::get('settings/theme-options', ThemeOptionSetting::class)->name('theme-options');
    Route::get('settings/menu', MenuManager::class)->name('menu');
    Route::post('settings/menu-form/{dataId?}', MenuForm::class)->name('menu-form');
    Route::post('icon-manager', IconSetting::class)->name('icon-setting');
    Route::post('color-manager', ColorSetting::class)->name('color-setting');
});
