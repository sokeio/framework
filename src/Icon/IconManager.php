<?php

namespace Sokeio\Icon;


class IconManager
{
    private $icons = [];
    private $cacheIcon = [];
    public function __construct()
    {
        $this->Register(TablerIcon::class)->Register(BootstrapIcon::class);
    }
    private static $instance;
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new IconManager();
        }
        return self::$instance;
    }
    public function Register($iconClass)
    {
        $this->icons[$iconClass] = $iconClass;
        return $this;
    }
    public function Assets()
    {
        foreach ($this->icons as $icon)
            if (is_subclass_of($icon, IconBase::class)) (new ($icon))->AddToAsset();
    }
    public function toArray()
    {
        if (!empty($this->cacheIcon))
            return $this->cacheIcon;
        $icons = [];
        foreach ($this->icons as $icon)
            if (is_subclass_of($icon, IconBase::class))
                $icons[] = (new ($icon))->toArray();
        $this->cacheIcon = $icons;

        return $icons;
    }
    public function getIconByKey($key)
    {
        $arr = $this->toArray();
        foreach ($arr as $item) {
            if ($item['key'] == $key) {
                return $item['items'];
            }
        }
        return [];
    }
    public function  getListBase()
    {
        $icons = [];
        foreach ($this->icons as $icon)
            if (is_subclass_of($icon, IconBase::class))
                $icons[] = (new ($icon))->toArray(false);
        return $icons;
    }
}
