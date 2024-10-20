<?php

namespace Sokeio\Support\Livewire\Concerns;


trait WithLivewireData
{
    protected function queryStringWithLivewireData()
    {
        return [
            'soQuery' => ['as' => 'so', 'rules' => 'array'],
        ];
    }
    public $soData = [];
    public $soQuery = [];
    public function query($key, $value = null)
    {
        data_set($this->soQuery, $key, $value);
        return $this;
    }
    public function getValueQuery($key, $default = null)
    {
        return data_get($this->soQuery, $key, $default);
    }
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
