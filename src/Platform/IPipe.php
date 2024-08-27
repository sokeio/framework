<?php

namespace Sokeio\Platform;

use Closure;

interface IPipe
{

    /**
     * @param $data
     * @param Closure $next
     * @return mixed
     */
    public function handle($data, Closure $next): mixed;
}
