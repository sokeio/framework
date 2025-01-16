<?php

namespace Sokeio;

use Illuminate\Support\Facades\Facade;
use Sokeio\MediaStorage\MediaStorageManager;

/**
 * @see \Sokeio\MediaStorage
 * 
 * @method static \Sokeio\MediaStorage\MediaStorageService get($service)
 * @method static void register($action, $path, $data)
 * @method static array action($service, $action, $path, $data)
 * @method static array getAll()
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
