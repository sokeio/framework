<?php

namespace Sokeio;

use Illuminate\Support\Facades\Facade;
use Sokeio\Support\MediaStorage\MediaStorageManager;

/**
 * @see \Sokeio\Support\MediaStorage
 * 
 * @method static \Sokeio\Support\MediaStorage\MediaStorageService get($type)
 * @method static void register($action, $path, $data)
 * 
 *
 */

class MediaStorage extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MediaStorageManager::class;
    }
}
