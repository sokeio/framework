<?php

namespace Sokeio\UI\Tab;

use Sokeio\UI\BaseUI;

class TabControl extends BaseUI
{
    private $tabItems = [];
    private $index = -1;
    public function tabItem(
        $title,
        $icon = null,
        $component = null,
        $view = null,
        $content = null,
        $active = false
    ) {
        $tab = new TabItem();
        $tab->title = $title;
        $tab->icon = $icon;
        $tab->component = $component;
        $tab->view = $view;
        $tab->content = $content;
        $tab->active = $active;
        $this->tabItems[++$this->index] = $tab;
        $tab->id = $this->index;
        return $this;
    }
    public function tabItemComponent(
        $title,
        $icon = null,
        $component = null,
        $active = false
    ) {

        return $this->tabItem($title, $icon, $component, null, null, $active);
    }
    public function tabItemView(
        $title,
        $icon = null,
        $view = null,
        $active = false
    ) {

        return $this->tabItem($title, $icon, null, $view, null, $active);
    }
    public function tabItemContent(
        $title,
        $icon = null,
        $content = null,
        $active = false
    ) {

        return $this->tabItem($title, $icon, null, null, $content, $active);
    }
    public function activeTabIndex($index)
    {

        return $this->render(function () use ($index) {
            $this->tabItems[$index]->active = true;
            foreach ($this->tabItems as $i => $tab) {
                if ($i !== $index) {
                    $tab->active = false;
                }
            }
        });
    }
    public function activeTab($callback)
    {
        if (is_string($callback)) {
            $callback = [$this, $callback];
        }
        foreach ($this->tabItems as $index => $tab) {
            if ($callback($tab)) {
                $this->activeTabIndex($index);
                break;
            }
        }
        return $this;
    }
    protected function initUI()
    {
        parent::initUI();
        $this->render(function () {
            $this->className('card');
        });
    }
    protected function headerView()
    {
        $html = '';
        foreach ($this->tabItems as $tab) {
            $html .= $tab->renderHeader();
        }
        return $html;
    }

    protected function contentView()
    {
        $html = '';
        foreach ($this->tabItems as $tab) {
            $html .= $tab->renderContent();
        }
        return $html;
    }

    public function view()
    {
        $attr = $this->getAttr();
        return <<<HTML
        <div {$attr}>
            <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                {$this->headerView()}
            </ul>
            </div>
            <div class="card-body">
            <div class="tab-content">
                {$this->contentView()}
            </div>
            </div>
        </div>
    HTML;
    }
}
