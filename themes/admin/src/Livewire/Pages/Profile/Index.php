<?php

namespace ByteTheme\Admin\Livewire\Pages\Profile;

use BytePlatform\Component;

class Index extends Component
{
    public function mount()
    {
        page_title('Profile');
    }
    public function render()
    {
        return view('theme::pages.profile.index');
    }
}
