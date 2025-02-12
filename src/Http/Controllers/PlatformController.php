<?php

namespace Sokeio\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route as FacadesRoute;
use Sokeio\Core\Attribute\RouteInfo;
use Sokeio\Enums\MethodType;
use Sokeio\Platform;

class PlatformController extends Controller
{
    #[RouteInfo(
        MethodType::GET,
        'platform/screenshot/{type}/{id}',
        name: 'platform.screenshot',
        where: [['id', '.*']],
        isWeb: true
    )]
    public function bannerScreenshot($type, $id)
    {
        return Platform::screenshot($type, $id);
    }
    #[RouteInfo(
        MethodType::GET,
        'platform/routes',
        enableKeyInSetting: 'PLATFORM_TABLE_ROUTE_ENABLE',
        enable: false,
        isWeb: true
    )]
    public function routes()
    {
        $routeCollection = FacadesRoute::getRoutes();

        echo "<table style='width:100%'>";
        echo "<tr>";
        echo "<td width='10%'><h4>HTTP Method</h4></td>";
        echo "<td width='10%'><h4>Route</h4></td>";
        echo "<td width='10%'><h4>Name</h4></td>";
        echo "<td width='70%'><h4>Corresponding Action</h4></td>";
        echo "</tr>";
        foreach ($routeCollection as $value) {
            echo "<tr>";
            echo "<td>" . $value->methods()[0] . "</td>";
            echo "<td>" . $value->uri() . "</td>";
            echo "<td>" . $value->getName() . "</td>";
            echo "<td>" . $value->getActionName() . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}
