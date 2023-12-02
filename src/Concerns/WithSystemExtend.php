<?php

namespace Sokeio\Concerns;

use Illuminate\Support\Facades\Cache;
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
    public function FileInfoJson()
    {
        return $this->getName() . ".json";
    }
    public function HookFilterPath()
    {
        return $this->getName() . '_path';
    }
    public function PathFolder()
    {
        return $this->getPath('');
    }
    public function getPath($path)
    {
        return path_by($this->getName(), $path);
    }
    public function getPathPublic($path)
    {
        return $this->getPath('public/' . $path);
    }
    public function PublicFolder()
    {
        return public_path($this->getName() . 's');
    }
    public function ResetData()
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
    public function LoadApp()
    {
        $this->Load($this->PathFolder());
    }
    public function RegisterApp()
    {
        foreach ($this->getAll() as $item) {
            if ($item->isActive()) {
                $item->DoRegister();
            }
        }
    }
    public function BootApp()
    {
        foreach ($this->getAll() as $item) {
            if ($item->isActive()) {
                $item->DoBoot();
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
            return $item->CheckName($name);
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
    public function Load($path)
    {
        $path = apply_filters($this->HookFilterPath(), $path);
        if ($files =  glob($path . '/*', GLOB_ONLYDIR)) {
            foreach ($files as $itemFile) {
                $item = $this->AddItem($itemFile);
                if ($item && $item->isActive()) {
                    if ($this->isRegisterBeforeLoad()) {
                        $item->DoRegister();
                    }
                }
            }
        }
    }
    public function getLinks()
    {
        $links = [];
        $isTheme = $this->getName() === 'theme';
        $themes = [];
        if ($isTheme) {
            $themes[] = env('PLATFORM_THEME_DEFAULT', 'none');
            $themes[] = Theme::AdminId();
            $themes[] = Theme::SiteId();
            $themes[] = 'tabler';
        }
        foreach ($this->getAll() as $item) {
            if ($item->isActive() || (isset($item->vendor) && $item->vendor === true) || ($isTheme && (in_array($item->getName(), $themes)))) {
                $links[] = [
                    $item->getPath('public') => public_path(platform_path($this->getName(), $item->name))
                ];
                if (isset($item->parent) && $perent = $item->parent) {
                    do {
                        $perent = $this->find($perent);
                        $links[] = [
                            $perent->getPath('public') => public_path(platform_path($this->getName(), $perent->name))
                        ];
                    } while ($perent && isset($perent->parent) && $perent = $perent->parent);
                }
            }
        }
        return $links;
    }
    public function AddItem($path, $vendor = false)
    {
        $path = realpath($path);
        if (isset($this->arrData[$path])) return null;
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
