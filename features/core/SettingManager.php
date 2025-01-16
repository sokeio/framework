<?php

namespace Sokeio\Core;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Sokeio\Models\Setting;

class SettingManager
{
    const CACHE_KEY = 'sokeio_settings';
    private $values = [];
    private $changed = [];
    public function __construct()
    {
        // check connection and table setting is exist
        if (Schema::hasTable((new Setting())->getTable())) {
            $this->values =  Cache::rememberForever(self::CACHE_KEY, function () {
                return Setting::all()->pluck('value', 'key')->toArray();
            });
        }
    }

    public function get($key, $default = null)
    {
        return Arr::get($this->values, $key, $default);
    }

    public function set($key, $value)
    {
        $this->values[$key] = $value;
        $this->changed[] = $key;
        return $this;
    }

    public function save()
    {
        foreach ($this->changed as $key) {
            Setting::updateOrCreate(['key' => $key], ['value' => $this->values[$key]]);
        }
        $this->changed = [];
        Cache::put(self::CACHE_KEY, $this->values);
        return $this;
    }
    public function clear()
    {
        Cache::forget(self::CACHE_KEY);
        $this->values = [];
        $this->changed = [];
        $this->load();
        return $this;
    }
    public function load()
    {
        $this->values =  Cache::rememberForever(self::CACHE_KEY, function () {
            return Setting::all()->pluck('value', 'key')->toArray();
        });
        return $this;
    }
}
