<?php

namespace Sokeio\Livewire;

use Sokeio\Component;
use Livewire\Attributes\On;
use Sokeio\Facades\Theme;

class Notifications extends Component
{
    public $page = 1;
    public $type = 0;
    // #[On('echo:notifications,NotificationAdd')]
    // public function notifyNew()
    // {
    //     // $this->dispatch('notifyNew');
    // }
    #[On('change-sidebar-admin')]
    public function changeSitebarAdmin($miniSidebar)
    {
        Theme::sitebarAdmin($miniSidebar == 'true');
        $this->skipRender();
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
        return notification()->render($this->page - 1, $this->type);
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
        return viewScope('sokeio::notifications.index');
    }
}
