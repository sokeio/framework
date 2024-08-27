<?php

namespace Sokeio\Platform;

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
    public function boot(): void
    {
        foreach ($this->arrItems as $itemInfo) {
            if (!$itemInfo->isActive() || $itemInfo->isVendor()) {
                continue;
            }

            if ($this->type === 'theme') {
                $isAdmin = Arr::get($itemInfo, 'admin');
                if (Platform::isUrlAdmin() && !$isAdmin) {
                    continue;
                }

                if (!Platform::isUrlAdmin() && $isAdmin) {
                    continue;
                }
            }
            $itemInfo->boot();
        }
    }
    public function loader(): void
    {
        foreach ($this->arrItems as $itemInfo) {
            if (!$itemInfo->isActive() && !$itemInfo->isVendor()) {
                continue;
            }

            if ($this->type === 'theme') {
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
        if (Arr::get($item, 'active')) {
            return true;
        }

        return ItemStatus::key($this->type)->check($item['id']);
    }
    public function setActive(array| ItemInfo|string $id): bool
    {
        $item = $this->getItem($id);

        if (!$item) {
            return false;
        }

        $statusKey = $this->type;
        $onlyOne = false;

        if ($this->type === 'theme') {
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


        if (!$item) {
            return;
        }

        $statusKey = $this->type;

        if ('theme' === $this->type && (int) $item->admin === 1) {
            $statusKey .= '_admin';
        }

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
        return collect($this->arrItems)->values()->all();
    }
    public function loadFromPath(string $path)
    {
        $pathDir = "{$path}/{$this->type}s";
        if (!File::exists($pathDir)) {
            return;
        }
        foreach (File::directories($pathDir) as $item) {
            $this->addFromPath($item);
        }
    }
    private function checkSkip($fileInfo)
    {
        if ($this->type === 'theme') {
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

            $this->arrItems[$pathFileJson] =
                new ItemInfo($path, $this,  $fileInfo);
            if (!$this->checkSkip($fileInfo)) {
                $this->arrItems[$pathFileJson]->register();
            }
        }
        return $this->arrItems[$pathFileJson];
    }
}
