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
            <a wire:ignore.self class="nav-link {$active}" data-bs-toggle="tab" href="#{$this->getKey()}"
             role="tab" aria-controls="{$this->id}" aria-selected="{$active}">
                <i class="{$icon} me-2"></i> <span>{$this->title}</span>
            </a>
        </li>
HTML;
    }
    public function isActive()
    {
        return $this->id === $this->tabControl->getTabActive();
    }
    public function renderContent()
    {
        $active = $this->isActive() ? 'show' : '';
        return <<<HTML
        <div wire:ignore.self class="tab-pane fade {$active}" id="{$this->getKey()}"
         role="tabpanel" aria-labelledby="{$this->id}" data-tab-title="{$this->title}">
            {$this->tabControl->renderBodyTab($this)}
        </div>
HTML;
    }
}
