<?php

namespace Sokeio\Support\Livewire\Concerns;

trait WithLivewireData
{
    public $soData = [];
    protected function data($key, $value = null)
    {
        $this->soData[$key] = $value;
        return $this;
    }
    protected function getDataKey($key, $default = null)
    {
        return $this->soData[$key] ?? $default;
    }
    protected function getRefId()
    {
        return $this->getDataKey('refId');
    }
    protected function getIsPage()
    {
        return $this->getDataKey('isPage', false);
    }
    protected function getIsAdmin()
    {
        return $this->getDataKey('isAdmin', false);
    }
}
