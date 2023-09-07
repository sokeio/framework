<?php

namespace BytePlatform\Livewire;

use BytePlatform\Component;
use Livewire\Attributes\On;

class Notifications extends Component
{
    public $title = 'Last updates';
    public $notifications = [[
        'title' => 'test 123',
        'description' => ' Change deprecated html tags to text decoration',
        'show_star' => true
    ]];
    public $showNewNotification = false;

    #[On('echo:notifications,NotificationAdd')]
    public function notifyNew()
    {
        $this->showNewNotification = true;
    }
    public function mount()
    {
    }
    public function render()
    {
        return view_scope('byte::notifications');
    }
}
