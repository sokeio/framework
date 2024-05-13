<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \Sokeio\Livewire\Dashboard\Index::class)->name('admin.dashboard');
Route::post('/dashboard-setting', routeTheme(\Sokeio\Livewire\Dashboard\Setting::class))
    ->name('admin.dashboard-setting');
Route::post('/form-widget-setting/{widgetId?}', routeTheme(\Sokeio\Livewire\Dashboard\FormWidgetSetting::class))
    ->name('admin.form-widget-setting');
