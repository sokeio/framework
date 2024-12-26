<?php

namespace Sokeio\Support\MediaStorage;

use Sokeio\Support\MediaStorage\Local\LocalService;

class MediaStorageManager
{
    private $default = 'local';
    /** @var MediaStorageService[] */
    private $services = [];
    public function __construct()
    {
        $this->register($this->default, new LocalService());
    }
    public function register($type, $service)
    {
        $this->services[$type] = $service;
    }
    public function get($type): MediaStorageService
    {
        if ($this->services[$type]) {
            return $this->services[$type];
        }
        return $this->services[$this->default];
    }
    public function action($type, $action, $path, $data)
    {
        return [
            'result' => $this->get($type)->action($action, $path, $data),
            'services' => collect($this->services)->map(function ($service, $key) {
                return [
                    'key' => $key,
                    'name' => $service->getName(),
                    'icon' => $service->getIcon(),
                ];
            })
        ];
    }
}
