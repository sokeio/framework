<?php

namespace Sokeio\UI;

class PipeUI
{
    public function handle(BaseUI $data, $next)
    {
        return $next($data);
    }
}
