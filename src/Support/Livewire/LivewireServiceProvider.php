<?php

namespace Sokeio\Support\Livewire;

use Sokeio\Support\Livewire\Support\SupportFormObjects\SupportFormObjects;

class LivewireServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->booting(function () {
            app('livewire')->componentHook(SupportFormObjects::class);
        });
    }
}
