<?php

namespace Sokeio\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Sokeio\Component;

class GlobalBody extends Component
{
    #[On('logout')]
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
    public $timer = 0;
    public function render()
    {
        return view('sokeio::livewire.global-body');
    }
    public function test()
    {
        $this->timer += 1;
    }
}
