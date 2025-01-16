<?php

namespace Sokeio;

use Illuminate\Support\Facades\Facade;
use Sokeio\Theme\TailwindcssManager;

/**
 * @see \Sokeio\Tailwindcss
 *
 * @method static void render($theme)
 *
 */
class Tailwindcss extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TailwindcssManager::class;
    }
}
