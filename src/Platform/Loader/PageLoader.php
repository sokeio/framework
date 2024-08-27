<?php

namespace Sokeio\Platform\Loader;

use Closure;
use Sokeio\Platform\IPipe;

class PageLoader implements IPipe
{
    public function handle($data, Closure $next): mixed
    {
        // echo __DIR__;
        return $next($data);
    }
}
