<?php

namespace Sokeio\Components;

class PipeUI
{
    public function handle(BaseUI $data, $next)
    {
        return $next($data);
    }
}
