<?php

namespace Sokeio\Platform\Concerns;

use Illuminate\Support\Facades\Log;
use Sokeio\Events\PlatformChanged;
use Sokeio\Facades\Platform;

trait WithAssetData
{

    private $data = [];
    public function setFavicon($value)
    {
        $this->data['favicon'] = $value;
    }
    public function setTitle($value)
    {
        $this->data['title'] = $value;
    }
    public function setDescription($value)
    {
        $this->data['description'] = $value;
    }
    public function setKeywords($value)
    {
        $this->data['keywords'] = $value;
    }
    public function getFavicon()
    {
        return $this->data['favicon'] ?? '';
    }

    public function getTitle()
    {
        return $this->data['title'] ?? '';
    }
    public function getDescription()
    {
        return $this->data['description'] ?? '';
    }
    public function getKeywords()
    {
        return $this->data['keywords'] ?? '';
    }
    public function setData($key, $value)
    {
        $this->data[$key] = $value;
    }
    public function getData($key, $default = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }
}
