<?php

namespace Sokeio\UI\Concerns;

use Livewire\Drawer\Utils;
use Sokeio\UI\SoUI;


trait DataShareUI
{
    private $dataShared = [];
    private $params = [];
    private SoUI|null $manager = null;
    private function getDataShared($key = null, $default = null)
    {
        if (isset($this->dataShared[$key])) {
            return $this->dataShared[$key];
        }
        if ($this->parent) {
            return $this->dataShared[$key] = $this->parent->{"get" . ucfirst($key)}($default);
        }
        return  $default;
    }
    private function setDataShared($key, $value)
    {
        $this->dataShared[$key] = $value;
        return $this;
    }
    private function clearDataShared($key = null)
    {
        if ($key) {
            unset($this->dataShared[$key]);
        } else {
            $this->dataShared = [];
        }
        return $this;
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
    public function group($value)
    {
        return $this->setDataShared('group', $value);
    }
    public function getGroup($default = null)
    {
        return $this->getDataShared('group', $default);
    }
    public function clearGroup()
    {
        return $this->clearDataShared('group');
    }
    public function viewFactory($value)
    {
        return $this->setDataShared('viewFactory', $value);
    }
    public function getViewFactory($default = null)
    {
        return $this->getDataShared('viewFactory', $default);
    }
    public function clearViewFactory()
    {
        return $this->clearDataShared('viewFactory');
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

    public function getManager(): SoUI
    {
        return $this->manager;
    }
    public function setManager(SoUI $manager)
    {
        $this->manager = $manager;
        $this->setupChild(fn($c) => $c->setManager($manager));
        return $this;
    }

    public function setParams($params)
    {
        if (!is_array($params)) {
            $params = [$params];
        }
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    public function clearParams()
    {
        $this->params = [];
        return $this;
    }
    public function getParams($key = null, $keyParam = null, $default = null)
    {
        if (!$this->params) {
            return $this->parent ? $this->parent->getParams($key, $keyParam, $default) : $default;
        }
        $value = $this->params;
        if ($keyParam && $key) {
            $value =  data_get($this->params[$key], $keyParam, $default);
        }
        if (!$keyParam && $key) {
            $value = $this->params[$key] ?? $default;
        }
        return $value;
    }
    public function makeView($view, $data = [], $mergeData = [])
    {
        return $this->getViewFactory()?->make($view, $data, $mergeData)
            ->with([...Utils::getPublicPropertiesDefinedOnSubclass($this->getWire()), 'soUI' => $this]);
    }
    public function viewRender($view, $data = [], $mergeData = [])
    {
        return $this->makeView($view, $data, $mergeData)->render();
    }
}
