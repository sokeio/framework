<?php

use Illuminate\Support\Facades\Route;
use Sokeio\Livewire\ThemeOptionSetting;

Route::get('theme-options', ThemeOptionSetting::class)->name('admin.theme-options');
