<?php

namespace Sokeio\Widgets;

use Illuminate\Support\ServiceProvider;
use Sokeio\Facades\Dashboard;

class WidgetServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //TODO: Add your package boot logic here
        Dashboard::Register(ModelCountWidget::class);
    }
}
