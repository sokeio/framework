<?php

use Illuminate\Support\Facades\Route;


Route::get('/', route_theme(\BytePlatform\Livewire\Dashboard::class))->name('admin.dashboard');
Route::post('/widget-setting/{widgetId?}', route_theme(\BytePlatform\Livewire\SettingWidget::class))->name('admin.widget-setting');
Route::get('/settings', route_theme(\ByteTheme\Admin\Livewire\Pages\Setting\Index::class))->name('admin.setting');
