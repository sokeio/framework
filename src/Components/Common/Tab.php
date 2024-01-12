<?php

namespace Sokeio\Components\Common;

use Sokeio\Components\Base;

class Tab extends Base
{
    protected function __construct($value)
    {
        parent::__construct($value);
    }
    public function DataItem($value)
    {
        $this->ClearCache();
        parent::DataItem($value);
        if (($tabs = $this->getTabs())) {
            foreach ($tabs as $item) {
                if (isset($item['content'])) {
                    if (is_array($item['content'])) {
                        foreach ($item['content'] as $column) {
                            $column->DataItem($this->getDataItem());
                        }
                    } else {
                        $item['content']->DataItem($this->getDataItem());
                    }
                }
            }
        }
        return $this;
    }
    public function LevelDataUI($value)
    {
        $this->ClearCache();
        parent::LevelDataUI($value);
        if (($tabs = $this->getTabs())) {
            foreach ($tabs as $item) {
                if (isset($item['content'])) {
                    if (is_array($item['content'])) {
                        foreach ($item['content'] as $column) {
                            $column->LevelDataUI($this->getLevelDataUI());
                        }
                    } else {
                        $item['content']->LevelDataUI($this->getLevelDataUI());
                    }
                }
            }
        }
        return $this;
    }
    public function boot()
    {
        if (($tabs = $this->getTabs())) {
            foreach ($tabs as $item) {
                if (isset($item['content'])) {
                    if (is_array($item['content'])) {
                        foreach ($item['content'] as $column) {
                            $column->Prex($this->getPrex());
                            $column->Manager($this->getManager());
                            $column->boot();
                        }
                    } else {
                        $item['content']->Prex($this->getPrex());
                        $item['content']->Manager($this->getManager());
                        $item['content']->boot();
                    }
                }
            }
        }
    }
    private $tabs;
    public function getTabs()
    {
        return $this->tabs ?? [];
    }
    public static function TabItem($title = '', $icon = '', $active = false)
    {
        return [
            'title' => $title,
            'icon' => $icon,
            'active' => $active == true
        ];
    }
    public function addTab($tabItem, $content)
    {
        if (is_string($tabItem)) {
            $tabItem = self::TabItem($tabItem);
        }
        $this->tabs[] = [
            ...$tabItem,
            'content' => $content,
        ];
        return $this;
    }
    public function getView()
    {
        return 'sokeio::components.common.tab';
    }
}
