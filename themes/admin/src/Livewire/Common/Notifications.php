<?php

namespace ByteTheme\Admin\Livewire\Common;

use BytePlatform\Component;

class Notifications extends Component
{
    public $title='Last updates';
    public $notifications = [[
        'title' => 'test 123',
        'description' => ' Change deprecated html tags to text decoration',
        'show_star' => true
    ]];
    public function mount()
    {
    }
    public function render()
    {
        return view_scope('theme::common.notifications');
    }
}
