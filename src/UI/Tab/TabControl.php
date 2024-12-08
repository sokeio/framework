<?php

namespace Sokeio\UI\Tab;

use Sokeio\UI\BaseUI;
use Sokeio\UI\Common\Div;

class TabControl extends BaseUI
{
    private $tabItems = [];
    private $index = -1;
    private $tabActive = null;
    private $iconSize = 2;
    public function iconSize($size = 2)
    {
        $this->iconSize = $size;
        return $this;
    }
    public function getIconSize()
    {
        return $this->iconSize;
    }
    public function getTabActive()
    {
        return $this->tabActive ?? 0;
    }
    public function tabItem(
        $title,
        $icon,
        $ui = null,
    ) {
        $tab = new TabItem($this);
        $tab->title = $title;
        $tab->icon = $icon;
        $tab->id = ++$this->index;
        if (!$ui) {
            $ui = Div::make()->text($title)->className('bg-warning text-bg-warning p-4 rounded');
        }
        $this->tabItems[$tab->id] = $tab;
        $this->child($ui, 'tabItemUI' . $tab->id);
        return $this;
    }
    public function renderBodyTab($item)
    {
        return $this->renderChilds('tabItemUI' . $item->id, ['tab' => $item]);
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
