<?php

namespace Sokeio;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sokeio\Theme
 * @method static void headRender()
 * @method static void bodyRender()
 * @method static void bodyEndRender()
 *
 */
class Theme extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sokeio_theme';
    }
}
