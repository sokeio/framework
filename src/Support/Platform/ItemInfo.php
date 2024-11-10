<?php

namespace Sokeio\Support\Platform;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Sokeio\ObjectJson;
use Sokeio\Platform;
use Sokeio\Support\Platform\Concerns\WithRegisterItemInfo;
use Sokeio\ServicePackage;
use Sokeio\Theme;
use Illuminate\Support\Str;

class ItemInfo extends ObjectJson
{
    use WithRegisterItemInfo;
    private ServicePackage|null $package = null;
    private $flgActive = null;
    private $flgVendor = null;
    public function getPackage(): ServicePackage|null
    {
        return $this->package;
    }
    public function setPackage(ServicePackage $package): self
    {
        $this->package = $package;
        return $this;
    }

    public function __construct(private $path, private $manager, $data = [])
    {
        $this->path = realpath($this->path);
        parent::__construct($data);
    }
    public function getManager(): ItemManager
    {
        return $this->manager;
    }
    public function isTheme()
    {
        return $this->getManager()->isTheme();
    }

    public function getPath($path = '')
    {
        if ($path) {
            return $this->path . '/' . $path;
        }
        return $this->path;
    }
    public function getScreenshot(): string
    {
        return route('platform.screenshot', [
            'type' => $this->manager->getItemType(),
            'id' => $this->id
        ]);
    }
    public function isVendor(): bool
    {
        if ($this->flgVendor === null) {
            $this->flgVendor = Platform::isVendor($this->path);
        }
        return $this->flgVendor;
    }
    public function isActive(): bool
    {
        if ($this->flgActive === null) {
            $this->flgActive = $this->manager->isActive($this);
        }
        return $this->flgActive;
    }
    public function isActiveOrVendor(): bool
    {
        return $this->isActive() || $this->isVendor();
    }
    public function setActive(): bool
    {
        return $this->manager->setActive($this);
    }
    public function block(): void
    {
        $this->manager->block($this);
    }
    public function getLayouts(): array
    {
        return  collect(File::allFiles($this->path . '/resources/views/layouts'))->map(function ($file) {
            // remove .blade.php
            return str_replace('.blade.php', '', $file->getRelativePathname());
        })->toArray();
    }
    private function getPublicName()
    {
        if ($this->isTheme()) {
            if ($this['admin']) {
                return 'admin';
            }
            return 'site';
        }
        return $this->name;
    }
    public function autoAssets(): void
    {
        $pathPublic = $this->path . '/public/';

        if (file_exists($pathPublic . 'build/manifest.json')) {
            $manifest = json_decode(file_get_contents($pathPublic . 'build/manifest.json'), true);
            $url = $this->getManager()->getUrl($this->getPublicName() . '/build');
            if (isset($manifest['resources/js/app.js']['file'])) {
                Theme::linkJs($url . '/' . $manifest['resources/js/app.js']['file']);
            }
            if (isset($manifest['resources/sass/app.scss']['file'])) {
                Theme::linkCss($url . '/' . $manifest['resources/sass/app.scss']['file']);
            }
        }
    }
    /**
     * Copy the public folder of this item to the public_path('platform/$itemType/$name')
     * if not exists.
     *
     * @return $this
     */
    public function makePublic()
    {
        $pathPublic = $this->path . '/public';
        if (file_exists($pathPublic)) {
            $pathTarget = $this->getManager()->getPathPublic();
            if (!file_exists($pathTarget)) {
                File::makeDirectory($pathTarget, 0775, true);
            }
            $pathTarget = $this->getManager()->getPathPublic($this->getPublicName());
            if (file_exists($pathTarget)) {
                return $this;
            }
            if (env('SOKEIO_PUBLIC_COPY')) {
                File::copyDirectory($pathPublic, $pathTarget);
                return $this;
            }
            try {
                // symlink
                app('files')->link($pathPublic, $pathTarget);
            } catch (\Exception $e) {
                Log::error($e);
            }
        }
        return $this;
    }
    public function delete(): void
    {
        $this->manager->delete($this->id);
    }
    public function getReadme(): string
    {
        $path = $this->path . '/README.md';

        if (file_exists($path)) {
            return Str::markdown(file_get_contents($path));
        }
        return '';
    }
    public function getVersion(): string
    {
        return $this->version ?? '1.0.0';
    }
    public function getTitle(): string
    {
        return $this->title ?? $this->name;
    }
    public function getLastVersion(): string
    {
        return '1.0.2';
    }
}
