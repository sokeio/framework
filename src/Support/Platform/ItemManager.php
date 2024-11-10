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
    protected $itemGenerate;
    private function __construct(private $type) {}
    protected $platformStatus;
    protected $platformStatusThemeAdmin;
    private $marketplate;
    public function getMarketplate(): Marketplate
    {
        return $this->marketplate ??= new Marketplate($this);
    }
    public function getPlatformStatus($isThemeAdmin = false): PlatformStatus
    {
        if ($this->isTheme() && $isThemeAdmin) {
            return $this->platformStatusThemeAdmin ??= PlatformStatus::key('theme_admin');
        }

        return $this->platformStatus ??= PlatformStatus::key($this->type);
    }
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
            $itemInfo->autoAssets();
            $itemInfo->makePublic();
        }
    }
    public function loader(): void
    {
        foreach ($this->arrItems as $itemInfo) {
            if (!$this->checkItemActive($itemInfo)) {
                continue;
            }
            $itemInfo->loader();
        }
    }
    public function getPath($path = ''): string
    {
        if ($path) {
            $path = '/' . $path;
        }
        return config("sokeio.platform.$this->type.path", '') . $path;
    }
    public function getPathPublic($path = ''): string
    {
        if ($path) {
            $path = '/' . $path;
        }
        return config("sokeio.platform.$this->type.public", '') . $path;
    }
    public function getUrl($path = ''): string
    {
        if ($path) {
            $path = '/' . $path;
        }
        return '/platform/' . $this->type . $path;
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

        $isActive = $this->getPlatformStatus(Arr::get($item, 'admin'))->check($item['id']);

        if (!$isActive && $this->isTheme() && $this->getPlatformStatus(Arr::get($item, 'admin'))->empty()) {
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
        $this->getPlatformStatus(Arr::get($item, 'admin'))->active($item['id'], $this->isTheme());

        return true;
    }
    public function block(array| ItemInfo|string $id): void
    {
        $item = $this->getItem($id);

        if (!$item || $this->isTheme()) {
            return;
        }

        $this->getPlatformStatus()->block($item['id']);
    }
    public function find($id): ?ItemInfo
    {
        return collect($this->arrItems)->where(function ($item) use ($id) {
            return $item->id == $id;
        })->first();
    }
    public function has($id): bool
    {
        return $this->find($id) !== null;
    }
    public function delete($id): bool
    {
        $item = $this->find($id);
        if (!$item) {
            return false;
        }
        if (File::exists($item->getPath())) {
            File::deleteDirectory($item->getPath());
        }
        unset($this->arrItems[$id]);
        return true;
    }
    public function findByName($name): ?ItemInfo
    {
        return collect($this->arrItems)->where(function ($item) use ($name) {
            return $item->name == $name;
        })->first();
    }
    public function hasByName($name): bool
    {
        return $this->findByName($name) !== null;
    }
    public function deleteByName($name): bool
    {
        $item = $this->findByName($name);
        if (!$item) {
            return false;
        }
        File::deleteDirectories($item->getPath());
        unset($this->arrItems[$item->id]);
        return true;
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
        $pathDir = realpath("{$path}/{$this->type}");
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
    public function generate($name)
    {
        if (!$this->itemGenerate) {
            $this->itemGenerate = new ItemGenerate($this);
        }
        return $this->itemGenerate->generate($name);
    }
}
