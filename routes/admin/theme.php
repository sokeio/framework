<?php

use Illuminate\Support\Facades\Route;
use Sokeio\Livewire\ColorSetting;
use Sokeio\Livewire\CookiesSetting;
use Sokeio\Livewire\IconSetting;
use Sokeio\Livewire\Item\GroupItemForm;
use Sokeio\Livewire\Item\ItemForm;
use Sokeio\Livewire\Item\ItemTable;
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

    Route::post('icon-manager', IconSetting::class)->name('icon-setting');
    Route::post('color-manager', ColorSetting::class)->name('color-setting');
    Route::get('settings/permalink', PermalinkSetting::class)->name('permalink-setting');
    Route::get('settings/cookies', CookiesSetting::class)->name('cookies-setting');
    routeCrud('item', ItemTable::class, ItemForm::class);
    Route::post('group-item/{dataId?}', GroupItemForm::class)->name('group-item.create');
    Route::post('/setting/shortcode', ShortcodeSetting::class)->name('shortcode-setting');
});
