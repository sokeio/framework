<?php

namespace Sokeio\Components\Common;

use Sokeio\Components\Base;

class Tab extends Base
{
    protected function childComponents()
    {
        return array_column($this->getTabs(), 'content') ?: [];
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
            'active' => $active === true
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
