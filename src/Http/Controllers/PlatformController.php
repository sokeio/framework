<?php

namespace Sokeio\Http\Controllers;

use Illuminate\Routing\Controller;
use Sokeio\Platform;

class PlatformController extends Controller
{
    public function bannerScreenshot($type, $id)
    {
        return Platform::screenshot($type, $id);
    }
}
