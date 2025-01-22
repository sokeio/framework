<?php

namespace Sokeio\Dashboard;

use Livewire\Livewire;
use Sokeio\Platform;
use Sokeio\Dashboard\Loader\WidgetLoader;

class DashboardServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        Platform::addLoader(WidgetLoader::class);
        $this->app->singleton('sokeio_dashboard', DashboardManager::class);
        Livewire::component('sokeio::widget-component', WidgetComponent::class);
    }
}
