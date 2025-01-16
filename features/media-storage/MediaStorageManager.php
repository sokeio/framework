<?php

namespace Sokeio\MediaStorage;

use Sokeio\MediaStorage\StorageDisk\StorageDiskService;

class MediaStorageManager
{
    private $default = 'local:public';
    /** @var MediaStorageService[] */
    private $services = [];
    public function __construct()
    {
        $this->default = setting('SOKEIO_MEDIA_STORAGE_DEFAULT', 'local:public');
        $this->register(StorageDiskService::make(
            'public',
            'local:public',
            'Local Public',
            function () {
                return setting('SOKEIO_MEDIA_STORAGE_PUBLIC_ENABLE', true);
            }
        ));
        $this->register(StorageDiskService::make(
            'private',
            'local:private',
            'Local Private',
            function () {
                return setting('SOKEIO_MEDIA_STORAGE_PRIVATE_ENABLE', false);
            }
        ));
    }
    public function register(MediaStorageService $service)
    {
        $this->services[$service->getKey()] = $service;
    }
    public function get($service): MediaStorageService
    {
        if (isset($this->services[$service]) && $this->services[$service]->when()) {
            return $this->services[$service];
        }
        if (isset($this->services[$this->default]) && $this->services[$this->default]->when()) {
            return $this->services[$this->default];
        }
        return collect($this->services)->where(fn($item) => $item->when())->first();
    }
    public function action($service, $action, $path, $data)
    {
        return [
            'result' => $this->get($service)->action($action, $path, $data),
            'services' => collect($this->services)->where(fn($item) => $item->when())->map(function ($item, $key) {
                return [
                    'key' => $key,
                    'name' => $item->getName(),
                    'icon' => $item->getIcon(),
                ];
            })
        ];
    }
    public function getAll()
    {
        return collect($this->services)->map(function ($item, $key) {
            return [
                'text' => $item->getName(),
                'value' => $key,
                'icon' => $item->getIcon(),
            ];
        })->values();
    }
}
