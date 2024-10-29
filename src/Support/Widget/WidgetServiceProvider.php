<?php

namespace Sokeio\Support\Widget;

use Livewire\Livewire;
use Sokeio\Platform;
use Sokeio\Support\Widget\WidgetComponent;
use Sokeio\Support\Widget\Loader\WidgetLoader;

class WidgetServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        Platform::addLoader(WidgetLoader::class);
        $this->app->singleton('sokeio_widget', WidgetManager::class);
        Livewire::component('sokeio::widget-component', WidgetComponent::class);
    }
   
}
