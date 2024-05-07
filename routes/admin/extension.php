<?php

use Illuminate\Support\Facades\Route;
use Sokeio\Livewire\Extentions\BuildCrud;
use Sokeio\Livewire\Extentions\Create;
use Sokeio\Livewire\Extentions\Index;
use Sokeio\Livewire\Extentions\CreateFile;

if (!function_exists('routeExtension')) {
    function routeExtension($name)
    {
        Route::get('/' . $name . 's', routeTheme(Index::class, ['ExtentionType' => $name]))
            ->name($name);
        Route::post('/' . $name . 's/create', routeTheme(Create::class, ['ExtentionType' => $name]))
            ->name($name . '.create');
        Route::post('/' . $name . 's/file/{ExtentionId}', routeTheme(CreateFile::class, ['ExtentionType' => $name]))
            ->name($name . '.create-file');
    }
}

Route::group(['as' => 'admin.extension.'], function () {
    routeExtension('plugin');
    routeExtension('module');
    routeExtension('theme');
    Route::get('build-crud', BuildCrud::class)->name('build-crud');
});
