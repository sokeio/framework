<?php

namespace Sokeio\Support\MediaStorage;

use Illuminate\Support\Facades\Route;
use Sokeio\Platform;

class MediaStorageServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        Platform::routeAdmin(function () {
            Route::post('platform/media-store', [MediaStorageController::class, 'action'])->name('platform.media-store');
        });
    }
    public function boot()
    {
        //
    }
}
