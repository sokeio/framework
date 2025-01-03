<?php

namespace Sokeio\Support\Platform;

use Closure;

interface IPipeLoader
{

    /**
     * @param $data
     * @param Closure $next
     * @return mixed
     */
    public function handle(ItemInfo $data, Closure $next): mixed;
}
