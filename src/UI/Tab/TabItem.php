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
    public $params;
    public $content;
    public function __construct(protected TabControl $tabControl) {}
    private function getKey()
    {
        return  $this->name ?? $this->id;
    }
    public function renderHeader()
    {
        $icon = $this->icon;
        if (!$icon) {
            $icon = 'ti ti-home';
        }
        $iconSize = $this->tabControl->getIconSize();
        if ($iconSize < 1) {
            $iconSize = 2;
        }
        if ($iconSize) {
            $icon .= ' fs-' . $iconSize;
        }
        $active = $this->isActive() ? 'active' : '';
        return <<<HTML
        <li class="nav-item">
            <a class="nav-link {$active}" data-bs-toggle="tab" href="#{$this->getKey()}"
             role="tab" aria-controls="{$this->id}" aria-selected="{$active}">
                <i class="{$icon} me-2"></i> <span>{$this->title}</span>
            </a>
        </li>
HTML;
    }
    private function renderBody()
    {
        if ($this->component) {
            return Livewire::mount($this->component);
        }
        if ($this->view) {
            return view($this->view, $this->params);
        }
        return $this->content ?? $this->title;
    }
    public function isActive()
    {
        return $this->id === $this->tabControl->getTabActive();
    }
    public function renderContent()
    {
        $active = $this->isActive() ? 'show' : '';
        return <<<HTML
        <div class="tab-pane fade {$active}" id="{$this->getKey()}"
         role="tabpanel" aria-labelledby="{$this->id}" data-tab-title="{$this->title}">
            {$this->renderBody()}
        </div>
HTML;
    }
}
