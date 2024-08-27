<?php

namespace Sokeio\Platform;

use Closure;

interface ILoader
{

    /**
     * @param $data
     * @param Closure $next
     * @return mixed
     */
    public function handle(ItemInfo $data, Closure $next): mixed;
}
