<?php

namespace Sokeio;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sokeio\PlatformLoader
 *
 * @method static mix apply($data)
 * @method static mix addPipe($pipe)
 */

class PlatformLoader extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sokeio_platform_loader';
    }
}
