<?php

namespace Sokeio\Controllers;

use Illuminate\Routing\Controller;

class PlatformController extends Controller
{
    public function bannerScreenshot($type, $id)
    {
        return [
            $type,
            $id
        ];
    }
}
