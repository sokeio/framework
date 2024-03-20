<?php

namespace Sokeio\Platform\Concerns;

use Illuminate\Support\Str;
use Sokeio\RouteEx;

trait WithThemeProcess
{

    public function getLayouts()
    {
        return $this->dataLayouts;
    }
    private $dataLayouts = [];
    public $dataThemes = [];
    private function findAndRegister($theme, $parentId = null)
    {
        if (!$parentId) {
            $parentId = $theme;
        }
        if (!isset($this->dataThemes[$parentId])) {
            $this->dataThemes[$parentId] = [];
        }
        $theme_data = $this->find($theme);
        if ($theme_data == null) {
            return null;
        }
        $this->dataThemes[$parentId][] = $theme_data;
        if ($parent = $theme_data['parent']) {
            $this->findAndRegister($parent, $parentId);
        }
        $theme_data->doRegister();
        foreach ($theme_data->getLayouts() as $layout) {
            $this->dataLayouts[] =  $layout;
        }
        return $theme_data;
    }
    private function findAndRegisterRoute($theme, $parentId = null)
    {
        if (!$parentId) {
            $parentId = $theme;
        }
        if (!isset($this->dataThemes[$parentId])) {
            $this->dataThemes[$parentId] = [];
        }
        $theme_data = $this->find($theme);
        if ($theme_data == null) {
            return null;
        }
        $this->dataThemes[$parentId][] = $theme_data;
        if ($parent = $theme_data['parent']) {
            $this->findAndRegisterRoute($parent, $parentId);
        }
        RouteEx::Load($theme_data->getPath('routes/'));
        return $theme_data;
    }
    private function findLocations($locations, $theme, $parentId = null)
    {
        if (!$parentId) {
            $parentId = $theme;
        }
        if (!isset($this->dataThemes[$parentId])) {
            $this->dataThemes[$parentId] = [];
        }
        $theme_data = $this->find($theme);
        if ($theme_data == null) {
            return $locations;
        }
        $this->dataThemes[$parentId][] = $theme_data;
        if ($parent = $theme_data['parent']) {
            $locations  =  $this->findLocations($locations, $parent, $parentId);
        }
        if (isset($theme_data['locations'])) {
            foreach ($theme_data['locations'] as $item) {
                $locations[$item] = $item;
            }
        }
        return  $locations;
    }
    private $locations = null;
    public function getLocations()
    {
        if ($this->locations) {
            return $this->locations;
        }
        $site = applyFilters(PLATFORM_THEME_FILTER_LAYOUT, $this->SiteId(), 0);
        $temps = $this->findLocations(['menu_main' => 'menu_main'], $site);
        $this->locations = array_keys($temps);
        return $this->locations;
    }
    public function registerRoute()
    {
        $this->findAndRegisterRoute(applyFilters(PLATFORM_THEME_FILTER_LAYOUT, $this->AdminId(), 1));
        $this->findAndRegisterRoute(applyFilters(PLATFORM_THEME_FILTER_LAYOUT, $this->SiteId(), 0));
    }
    public function registerTheme()
    {
        $this->findAndRegister(env('PLATFORM_THEME_DEFAULT', 'none'));
        $this->findAndRegister(applyFilters(PLATFORM_THEME_FILTER_LAYOUT, $this->AdminId(), 1));
        $this->findAndRegister(applyFilters(PLATFORM_THEME_FILTER_LAYOUT, $this->SiteId(), 0));
    }
    
}
