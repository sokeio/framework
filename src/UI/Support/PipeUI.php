<?php

namespace Sokeio\UI\Support;

use Sokeio\UI\BaseUI;

class PipeUI
{
    public function handle(BaseUI $data, $next)
    {
        return $next($data);
    }
}
