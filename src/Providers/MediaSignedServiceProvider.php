<?php

namespace Sokeio\Providers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class MediaSignedServiceProvider extends ServiceProvider
{
    public function boot()
    {
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
