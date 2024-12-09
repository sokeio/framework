<?php

namespace Sokeio\UI\Concerns;


trait DataShareUI
{
    private $arrayShare = ['context', 'prefix', 'groupField'];
    private $shareContext = null;
    private $sharePrefix = null;
    private $shareGroupField = null;
    private $params = [];


    private function getDataShared($key = null, $default = null)
    {
        if ($this->{'share' . ucfirst($key)}) {
            return $this->{'share' . ucfirst($key)};
        }
        return  $default;
    }
    private function setDataShared($key, $value)
    {
        if ($value && $this->{'share' . ucfirst($key)}) {
            return $this;
        }
        $this->{'share' . ucfirst($key)} = $value;
        return $this->boot(function ($base) use ($key, $value) {
            $base->setupChild(fn($c) => $c->{$key}($value));
        });
    }
    private function clearDataShared($key = null)
    {
        return $this->setDataShared($key, null);
    }
    public function context($value)
    {
        return $this->setDataShared('context', $value);
    }
    public function getContext($default = null)
    {
        return $this->getDataShared('context', $default);
    }
    public function clearContext()
    {
        return $this->clearDataShared('context');
    }
    public function prefix($value)
    {
        return $this->setDataShared('prefix', $value);
    }
    public function getPrefix($default = null)
    {
        return $this->getDataShared('prefix', $default);
    }
    public function clearPrefix()
    {
        return $this->clearDataShared('prefix');
    }

    public function groupField($value)
    {
        return $this->setDataShared('groupField', $value);
    }
    public function getGroupField($default = null)
    {
        return $this->getDataShared('groupField', $default);
    }
    public function clearGroupField()
    {
        return $this->clearDataShared('groupField');
    }
    public function setParams($params)
    {
        if (!is_array($params)) {
            $params = [$params];
        }
        $this->params = array_merge($this->params, $params);
        return $this->setupChild(fn($c) => $c->setParams($params));
    }

    public function clearParams()
    {
        $this->params = [];
        return $this;
    }
    public function getParams($key = null, $keyParam = null, $default = null)
    {
        $value = $this->params;
        if ($keyParam && $key) {
            $value =  data_get($this->params[$key], $keyParam, $default);
        }
        if (!$keyParam && $key) {
            $value = $this->params[$key] ?? $default;
        }
        return $value;
    }
    public function clearDataShare()
    {
        $this->clearParams();
        $this->clearGroupField();
    }
    public function onlyChildClear()
    {
        foreach ($this->arrayShare  as $key) {
            $this->setupChild(fn($c) => $c->{$key}(null));
        }
    }
    public function shareToChild()
    {
        foreach ($this->arrayShare  as $item) {
            $this->setDataShared($item, $this->getDataShared($item));
        }
    }
}
