<?php

namespace Sokeio;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sokeio\LivewireLoader
 *
 * @method static void register($path, $namespace, $aliasPrefix = '', $callback = null)
 * @method static mix getComponents()
 */

class LivewireLoader extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sokeio_livewire_loader';
    }
}
