<?php

namespace Sokeio\UI\Tab;

use Sokeio\UI\BaseUI;

class TabControl extends BaseUI
{
    private $tabItems = [];
    private $index = -1;
    private $tabActive = null;
    private $iconSize = 2;
    public function iconSize($size =2){
        $this->iconSize = $size;
        return $this;
    }
    public function getIconSize(){
        return $this->iconSize;
    }
    public function getTabActive()
    {
        return $this->tabActive ?? 0;
    }
    public function tabItem(
        $title,
        $icon = null,
        $fnSetup = null,
    ) {
        $tab = new TabItem($this);
        $tab->title = $title;
        $tab->icon = $icon;
        if ($fnSetup) {
            call_user_func($fnSetup, $tab);
        }
        $tab->id = ++$this->index;
        $this->tabItems[$tab->id] = $tab;
        return $this;
    }
    public function tabItemComponent(
        $title,
        $icon = null,
        $component = null,
        $params = null,
    ) {

        return $this->tabItem($title, $icon, function ($tab) use ($component, $params) {
            $tab->component = $component;
            $tab->params = $params;
        });
    }
    public function tabItemView(
        $title,
        $icon = null,
        $view = null,
        $params = null
    ) {

        return $this->tabItem($title, $icon, function ($tab) use ($view, $params) {
            $tab->view = $view;
            $tab->params = $params;
        });
    }
    public function tabItemContent(
        $title,
        $icon = null,
        $content = null
    ) {

        return $this->tabItem($title, $icon, function ($tab) use ($content) {
            $tab->content = $content;
        });
    }
    public function activeTabIndex($index)
    {
        $this->tabActive = $index;
        return $this;
    }
    public function activeTab($callback)
    {
        if (is_string($callback)) {
            $callback = [$this, $callback];
        }
        foreach ($this->tabItems as $index => $tab) {
            if ($callback($tab)) {
                return  $this->activeTabIndex($index);
            }
        }
        return $this;
    }
    protected function initUI()
    {
        parent::initUI();
        $this->render(function () {
            $this->className('sokeio-tab');
        });
    }
    public function vertical()
    {
        return $this->render(function () {
            $this->className('sokeio-tab-vertical');
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
            <ul class="nav nav-tabs sokeio-tab-header" data-bs-toggle="tabs" role="tablist">
                {$this->headerView()}
            </ul>
            <div class="sokeio-tab-content">
                {$this->contentView()}
            </div>
        </div>
    HTML;
    }
}
