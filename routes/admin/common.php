<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \Sokeio\Livewire\Dashboard::class)->name('admin.dashboard');
Route::post('/dashboard-setting/{widgetId?}', routeTheme(\Sokeio\Livewire\DashboardSetting::class))
    ->name('admin.dashboard-setting');
