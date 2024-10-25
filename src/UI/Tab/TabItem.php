<?php

namespace Sokeio\UI\Tab;

use Livewire\Livewire;

class TabItem
{
    public $icon;
    public $id;
    public $name;
    public $title;
    public $component;
    public $view;
    public $content;
    public $active = false;
    public function renderHeader()
    {
        $icon = $this->icon;
        if (!$icon) {
            $icon = 'ti ti-home';
        }
        $active = $this->active ? 'active' : '';
        return <<<HTML
        <li class="nav-item">
            <a class="nav-link {$active}" data-bs-toggle="tab" href="#{$this->id}"
             role="tab" aria-controls="{$this->id}" aria-selected="{$this->active}">
                <i class="{$icon}  fs-3 me-1"></i> <span>{$this->title}</span>
            </a>
        </li>
HTML;
    }
    public function renderContent()
    {
        if ($this->component) {
            return Livewire::mount($this->component);
        }
        if ($this->view) {
            return view($this->view);
        }
        return $this->content ?? '';
    }
}
