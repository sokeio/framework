<?php

namespace Sokeio\Widgets;

use Illuminate\Support\ServiceProvider;
use Sokeio\Facades\Dashboard;

class WidgetServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Dashboard::Register(UserTotalWidget::class);
        Dashboard::Register(RoleTotalWidget::class);
        Dashboard::Register(PermissionTotalWidget::class);
        Dashboard::Register(TestWidget::class);
    }
}
