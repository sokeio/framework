<?php

namespace Sokeio\Livewire;

use Sokeio\Component;
use Livewire\Attributes\On;

class Notifications extends Component
{
    public $page = 1;
    public $type = 0;
    #[On('echo:notifications,NotificationAdd')]
    public function notifyNew()
    {
        $this->dispatch('notifyNew');
    }
    public function changeType($type)
    {
        $this->page = 0;
        $this->type = $type;
        return $this->loadMore();
    }
    public function loadMore()
    {
        $this->skipRender();
        $this->page = $this->page + 1;
        return notification()->Render($this->page - 1, $this->type);
    }
    public function TickReadAll()
    {
        notification()->tickReadAll();
        return $this->changeType($this->type);
    }
    public function TickRead($id)
    {
        $this->skipRender();
        notification()->tickRead($id);
    }
    public function render()
    {
        return view_scope('sokeio::notifications.index');
    }
}
