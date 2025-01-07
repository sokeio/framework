<?php

namespace Sokeio\Support\MediaStorage;

use Illuminate\Support\Facades\Log;
use Sokeio\Support\MediaStorage\StorageDisk\StorageDiskService;

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
    public function get($type): MediaStorageService
    {
        if (isset($this->services[$type]) && $this->services[$type]->when()) {
            return $this->services[$type];
        }
        if (isset($this->services[$this->default]) && $this->services[$this->default]->when()) {
            return $this->services[$this->default];
        }
        return collect($this->services)->where(fn($service) => $service->when())->first();
    }
    public function action($type, $action, $path, $data)
    {
        return [
            'result' => $this->get($type)->action($action, $path, $data),
            'services' => collect($this->services)->where(fn($service) => $service->when())->map(function ($service, $key) {
                return [
                    'key' => $key,
                    'name' => $service->getName(),
                    'icon' => $service->getIcon(),
                ];
            })
        ];
    }
    public function getAll()
    {
        return collect($this->services)->map(function ($service, $key) {
            return [
                'text' => $service->getName(),
                'value' => $key,
                'icon' => $service->getIcon(),
            ];
        })->values();
    }
}
