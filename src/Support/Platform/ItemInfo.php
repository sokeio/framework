<?php

namespace Sokeio\Support\Platform;

use Illuminate\Support\Facades\File;
use Sokeio\ObjectJson;
use Sokeio\Platform;
use Sokeio\Support\Platform\Concerns\WithRegisterItemInfo;
use Sokeio\ServicePackage;
use Sokeio\Theme;

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

    public function getPath()
    {
        return $this->path;
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
    public function autoAssets(): void
    {
        $pathPublic = $this->path . '/public/';

        if (file_exists($pathPublic . 'build/manifest.json')) {
            $manifest = json_decode(file_get_contents($pathPublic . 'build/manifest.json'), true);
            $url = $this->getManager()->getUrl($this->name . '/build');
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
            $pathTarget = $this->getManager()->getPathPublic($this->name);
            if (!file_exists($pathTarget)) {
                if (env('SOKEIO_PUBLIC_COPY')) {
                    File::copyDirectory($pathPublic, $pathTarget);
                } else {
                    // symlink
                    app('files')->link($pathPublic, $pathTarget);
                }
            }
        }
        return $this;
    }
    public function delete(): void
    {
        $this->manager->delete($this->id);
    }
}
