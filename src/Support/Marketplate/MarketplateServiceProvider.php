<?php

namespace Sokeio\Support\Marketplate;

use Illuminate\Support\Facades\Route;
use Sokeio\Http\Middleware\Authenticate;
use Sokeio\Http\Middleware\JwtUser;
use Sokeio\Platform;

class MarketplateServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
       // Register the service here
    }
}
