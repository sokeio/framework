<?php

namespace Sokeio\Support\Platform;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Sokeio\Platform;

class ItemManager
{
    private static $instances = [];
    public static function getInstance(string $type): ItemManager
    {
        return self::$instances[$type] ??= new self($type);
    }
    /**
     * @var array
     * [
     *  'path' => ItemInfo
     * ]
     */
    private $arrItems = [];
    private function __construct(private $type) {}
    public function getItemType()
    {
        return $this->type;
    }
    public function getItemActive(): array
    {
        return collect($this->arrItems)->where('isActive', true)->values()->all();
    }
    public function isTheme(): bool
    {
        return $this->type === 'theme';
    }
    private function checkItemActive($itemInfo, $isVendor = true)
    {
        if ($this->isTheme()) {
            return $itemInfo->isActive();
        }
        return ($itemInfo->isActive() && !$itemInfo->isVendor()) || ($itemInfo->isVendor() === $isVendor);
    }
    public function boot(): void
    {
        foreach ($this->arrItems as $itemInfo) {
            if (!$this->checkItemActive($itemInfo)) {
                continue;
            }

            if ($this->isTheme()) {
                $isAdmin = Arr::get($itemInfo, 'admin');
                if (Platform::isUrlAdmin() && !$isAdmin) {
                    continue;
                }

                if (!Platform::isUrlAdmin() && $isAdmin) {
                    continue;
                }
            }
            $itemInfo->boot();
            $itemInfo->makePublic();
        }
    }
    public function loader(): void
    {
        foreach ($this->arrItems as $itemInfo) {
            if (!$this->checkItemActive($itemInfo)) {
                continue;
            }

            if ($this->isTheme()) {
                $isAdmin = Arr::get($itemInfo, 'admin');
                if (Platform::isUrlAdmin() && !$isAdmin) {
                    continue;
                }

                if (!Platform::isUrlAdmin() && $isAdmin) {
                    continue;
                }
            }
            $itemInfo->loader();
        }
    }

    private function getItem(array| ItemInfo|string $id)
    {
        if (is_string($id)) {
            return $this->find($id);
        }

        return $id;
    }
    public function isActive(array| ItemInfo|string $id): bool
    {
        $item = $this->getItem($id);

        if (!$item) {
            return false;
        }

        $statusKey = $this->type;

        if ($this->isTheme() && Arr::get($item, 'admin')) {
            $statusKey = 'theme_admin';
        }

        $isActive = ItemStatus::key($statusKey)->check($item['id']);

        if (!$isActive && $this->isTheme() && ItemStatus::key($statusKey)->empty()) {
            $themeConfigKey = Arr::get($item, 'admin') ? 'admin_theme' : 'site_theme';
            $isActive = $item->id === config("sokeio.$themeConfigKey");
        }

        return $isActive;
    }
    public function setActive(array| ItemInfo|string $id): bool
    {
        $item = $this->getItem($id);

        if (!$item) {
            return false;
        }

        $statusKey = $this->type;
        $onlyOne = false;

        if ($this->isTheme()) {
            if (Arr::get($item, 'admin')) {
                $statusKey = 'theme_admin';
            }

            $onlyOne = true;
        }

        ItemStatus::key($statusKey)->active($item['id'], $onlyOne);

        return true;
    }
    public function block(array| ItemInfo|string $id): void
    {
        $item = $this->getItem($id);

        if (!$item || $this->isTheme()) {
            return;
        }

        $statusKey = $this->type;

        ItemStatus::key($statusKey)->block($item['id']);
    }
    public function find($id): ?ItemInfo
    {
        return collect($this->arrItems)->where(function ($item) use ($id) {
            return $item->id == $id;
        })->first();
    }
    public function getAll()
    {
        return collect($this->arrItems);
    }
    public function getActiveAll()
    {
        return collect($this->arrItems)->where(function ($item) {
            return $item->isActive();
        });
    }
    public function loadFromPath(string $path)
    {
        $pathDir = realpath("{$path}/{$this->type}s");
        if (!File::exists($pathDir)) {
            return;
        }
        foreach (File::directories($pathDir) as $item) {
            $this->addFromPath($item);
        }
    }
    private function checkSkip($fileInfo)
    {
        if ($this->isTheme() && $fileInfo) {
            return !$this->isActive($fileInfo);
        }
        return false;
    }

    public function addFromPath(string $path)
    {
        $pathFileJson = realpath("{$path}/{$this->type}.json");
        if (!file_exists($pathFileJson)) {
            return null;
        }
        if (!isset($this->arrItems[$pathFileJson])) {
            $fileInfo = json_decode(file_get_contents($pathFileJson), true);
            if (!$fileInfo) {
                return null;
            }
            $this->arrItems[$pathFileJson] =
                new ItemInfo($path, $this,  $fileInfo);
            if (!$this->checkSkip($this->arrItems[$pathFileJson])) {
                $this->arrItems[$pathFileJson]->register();
            }
        }
        return $this->arrItems[$pathFileJson];
    }
}
