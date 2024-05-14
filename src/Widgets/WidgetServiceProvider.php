<?php

namespace Sokeio\Widgets;

use Illuminate\Support\ServiceProvider;
use Sokeio\Facades\Dashboard;

class WidgetServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Dashboard::Register(ModelCountWidget::class);
        Dashboard::Register(ChartDataWidget::class);
    }
}
