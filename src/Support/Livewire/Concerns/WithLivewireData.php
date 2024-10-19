<?php

namespace Sokeio\Support\Livewire\Concerns;

use Livewire\Attributes\Url;

trait WithLivewireData
{
    protected function queryStringWithLivewireData()
    {
        return [
            'soData' => ['as' => 'so'],
        ];
    }
    public $soData = [];
    public function data($key, $value = null)
    {
        data_set($this->soData, $key, $value);
        return $this;
    }
    protected function getDataKey($key, $default = null)
    {
        return data_get($this->soData, $key, $default);
    }
    protected function getRefId()
    {
        return $this->getDataKey('refId');
    }
    protected function getIsPage()
    {
        return $this->getDataKey('isPage', false);
    }
    public function getRouteName($prefix = null)
    {
        return $this->getDataKey('routeName') . ($prefix ? '.' . $prefix : '');
    }
    protected function getIsAdmin()
    {
        return $this->getDataKey('isAdmin', false);
    }
}
