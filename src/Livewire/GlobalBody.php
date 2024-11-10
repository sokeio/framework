<?php

namespace Sokeio\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Sokeio\Component;

class GlobalBody extends Component
{
    // #[Locked]
    public $isAuth = false;
    public $authUser = null;
    #[On('logout')]
    public function logout()
    {
        Auth::logout();
        return $this->redirectCurrent();
    }
    public function test()
    {
        $this->isAuth = !$this->isAuth;
    }
    public function mount()
    {
        $this->refreshGlobal();
    }
    public function refreshGlobal()
    {
        $this->isAuth = Auth::check();
        if ($this->isAuth) {
            $this->authUser = Auth::user()->toArray();
        } else {
            $this->authUser = null;
        }
    }
    public function render()
    {
        return view('sokeio::livewire.global-body');
    }
}
