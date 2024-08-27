<?php

namespace Sokeio\Livewire;

use Sokeio\Livewire\Support\SupportFormObjects\SupportFormObjects;

class LivewireServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->booting(function () {
            app('livewire')->componentHook(SupportFormObjects::class);
        });
        $this->app->singleton('sokeio_livewire_loader', function ($app) {
            return new LivewireLoaderManager(app: $app);
        });
    }
}
