<?php

use Illuminate\Support\Facades\Route;
use Sokeio\Livewire\ColorSetting;
use Sokeio\Livewire\CookiesSetting;
use Sokeio\Livewire\IconSetting;
use Sokeio\Livewire\Menu\MenuForm;
use Sokeio\Livewire\Menu\MenuItemForm;
use Sokeio\Livewire\ThemeOptionSetting;
use Sokeio\Livewire\Menu\MenuManager;
use Sokeio\Livewire\PermalinkSetting;
use Sokeio\Livewire\ShortcodeSetting;

Route::group(['as' => 'admin.'], function () {
    Route::get('settings/theme-options', ThemeOptionSetting::class)->name('extension.theme.option');
    Route::get('settings/menu', MenuManager::class)->name('extension.theme.menu');
    Route::post('settings/menu-form/{dataId?}', MenuForm::class)->name('menu-form');
    Route::post('settings/menu-item-form/{dataId?}', MenuItemForm::class)->name('menu-item-form');

    Route::post('settings/icon-manager', IconSetting::class)->name('icon-setting');
    Route::post('settings/color-manager', ColorSetting::class)->name('color-setting');
    Route::post('settings/shortcode', ShortcodeSetting::class)->name('shortcode-setting');

    Route::get('system/permalink', PermalinkSetting::class)->name('system.permalink-setting');
    Route::get('system/cookies', CookiesSetting::class)->name('system.cookies-setting');
    Route::get('system/log-viewers', routeTheme(\Sokeio\Livewire\LogsViewer::class))
        ->name('system.log-viewer');
    Route::get('system/clean-system-tool', routeTheme(\Sokeio\Livewire\CleanSystemTool::class))
        ->name('system.clean-system-tool');
});
