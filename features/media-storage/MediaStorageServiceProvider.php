<?php

namespace Sokeio\MediaStorage;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
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
        if (setting('SOKEIO_MEDIA_STORAGE_PRIVATE_ENABLE', false)) {
            if (!config('filesystems.disks.private')) {
                config(
                    [
                        'filesystems.disks.private' => [
                            'driver' => 'local',
                            'root' => storage_path('app/private'),
                        ],
                    ]
                );
            }

            if (!File::exists(storage_path('app/private'))) {
                File::makeDirectory(storage_path('app/private'));
            }
        }


        Route::get('media-file-signed/{disk}/{path}', function ($disk, $path) {
            if (!$disk) {
                abort(404);
            }
            $disk = Crypt::decryptString($disk);
            if (!$disk) {
                abort(404);
            }
            return Storage::disk($disk)->exists($path) ? Storage::disk($disk)->response($path) : abort(404);
        })->name('sokeio.media-file.signed')
            ->where('path', '.*')
            ->middleware(['signed', 'web']);
        Storage::buildTemporaryUrlsUsing(function ($path, $expiration, $options) {
            return URL::temporarySignedRoute(
                'sokeio.media-file.signed',
                $expiration,
                array_merge($options, [
                    'path' => $path,
                    'disk' => Crypt::encryptString($options['disk'])
                ])
            );
        });
    }
}
