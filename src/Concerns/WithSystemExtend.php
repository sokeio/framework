<?php

namespace Sokeio\Concerns;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Sokeio\Platform\DataInfo;
use Sokeio\Facades\Theme;

trait WithSystemExtend
{
    public function __construct()
    {
        $this->arrData = collect([]);
    }
    private $arrData = [];
    public function isRegisterBeforeLoad()
    {
        return true;
    }
    public function getName()
    {
        return 'info';
    }
    public function fileInfoJson()
    {
        return $this->getName() . ".json";
    }
    public function hookFilterPath()
    {
        return $this->getName() . '_path';
    }
    public function pathFolder()
    {
        return $this->getPath('');
    }
    public function getPath($path)
    {
        return pathBy($this->getName(), $path);
    }
    public function getPathPublic($path)
    {
        return $this->getPath('public/' . $path);
    }
    public function publicFolder()
    {
        return public_path($this->getName() . 's');
    }
    public function resetData()
    {
        $this->arrData = collect($this->arrData)->where(function ($item) {
            return $item->isVendor();
        })->toBase();
    }
    public function getModels()
    {
        $arr = [];
        foreach ($this->getAll() as $item) {
            if ($item->isActive() || $item->isVendor()) {
                $arr = [...$arr, ...$item->getModels()];
            }
        }
        return $arr;
    }
    public function loadApp()
    {
        $this->Load($this->pathFolder());
    }
    public function registerApp()
    {
        foreach ($this->getAll() as $item) {
            if ($item->isActive()) {
                $item->doRegister();
            }
        }
    }
    public function bootApp()
    {
        foreach ($this->getAll() as $item) {
            if ($item->isActive()) {
                $item->doBoot();
            }
        }
    }
    /**
     * Get the data.
     *
     * @return \Illuminate\Support\Collection<string, \Sokeio\Platform\DataInfo>
     */
    public function getAll()
    {
        return $this->arrData;
    }
    public function getDataAll()
    {
        return $this->getAll()->where(function (DataInfo $item) {
            return !$item->hide;
        });
    }
    /**
     * Find item by name.
     * @param string $name
     *
     * @return  DataInfo
     */
    public function find($name)
    {
        return $this->getAll()->where(function (DataInfo $item) use ($name) {
            return $item->checkName($name);
        })->first();
    }
    public function has($name)
    {
        return $this->find($name) != null;
    }
    public function delete($name)
    {
        $base = $this->find($name);
        if ($base) {
            $base->delete();
        }
    }
    public function load($path)
    {
        $path = apply_filters($this->hookFilterPath(), $path);
        if ($files =  glob($path . '/*', GLOB_ONLYDIR)) {
            foreach ($files as $itemFile) {
                $item = $this->addItem($itemFile);
                if ($item && $item->isActive() && $this->isRegisterBeforeLoad()) {
                    $item->doRegister();
                }
            }
        }
    }
    public function isTheme()
    {
        return $this->getName() === 'theme';
    }
    private function checkItemActive($item, $themes)
    {
        if ($item->isActive() || (isset($item->vendor) && $item->vendor === true)) {
            return true;
        }
        if ($this->isTheme() && (in_array($item->getName(), $themes))) {
            return true;
        }
        return false;
    }
    public function getLinks()
    {
        $links = [];
        $themes = [];
        if ($this->isTheme()) {
            $themes[] = env('PLATFORM_THEME_DEFAULT', 'none');
            $themes[] = Theme::adminId();
            $themes[] = Theme::SiteId();
            $themes[] = 'tabler';
        }
        foreach ($this->getAll() as $item) {
            if ($this->checkItemActive($item, $themes)) {
                $links[] = [
                    $item->getPath('public') => public_path(platformPath($this->getName(), $item->name))
                ];
                if (isset($item->parent) && $perent = $item->parent) {
                    do {
                        $perent = $this->find($perent);
                        $links[] = [
                            $perent->getPath('public') => public_path(platformPath($this->getName(), $perent->name))
                        ];
                    } while ($perent && isset($perent->parent) && $perent = $perent->parent);
                }
            }
        }
        return $links;
    }
    public function addItem($path, $vendor = false)
    {
        $path = realpath($path);
        if (isset($this->arrData[$path])) {
            return null;
        }
        $this->arrData[$path] = new DataInfo($path, $this);
        $this->arrData[$path]->vendor = $vendor;
        return $this->arrData[$path];
    }
    public function getUsed()
    {
        return Cache::get($this->getName() . '_used');
    }
    public function setUsed($name)
    {
        Cache::forever($this->getName() . '_used', $name);
    }
    public function forgetUsed()
    {
        Cache::forget($this->getName() . '_used');
    }
    public function update(string $name)
    {
        $base = $this->find($name);
        if ($base) {
            $base->update();
        }
    }
}
