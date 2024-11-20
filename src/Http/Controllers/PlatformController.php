<?php

namespace Sokeio\Http\Controllers;

use Illuminate\Routing\Controller;
use Sokeio\Attribute\Route;
use Sokeio\Enums\MethodType;
use Sokeio\Platform;

class PlatformController extends Controller
{
    #[Route(MethodType::GET, '/screenshot/{type}/{id}', name: 'platform.screenshot', where: [['id', '.*']])]
    public function bannerScreenshot($type, $id)
    {
        return Platform::screenshot($type, $id);
    }
}
