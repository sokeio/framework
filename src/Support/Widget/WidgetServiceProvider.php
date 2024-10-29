<?php

namespace Sokeio\Support\Widget;

use Sokeio\Platform;
use Sokeio\Support\Widget\Loader\WidgetLoader;

class WidgetServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        Platform::addLoader(WidgetLoader::class);
    }
}
