<?php

namespace Sokeio\Platform\Concerns;

use Illuminate\Support\Str;

trait WithDataInfoProperty
{
    public function getPath($_path = '')
    {
        return $this->path . ($_path ? ('/' . $_path) : '');
    }
    public function getPublic($_path = '')
    {
        return $this->public . ($_path ? ('/' . $_path) : '');
    }
    public function getPathPublic($path)
    {
        return $this->getPath('public/' . $path);
    }
    public function url($_path = '')
    {
        return asset(platformPath($this->baseType, $this->getName() . ($_path ? ('/' . $_path) : '')));
    }
    public function getFiles()
    {
        return $this['files'] ?? [];
    }
    public function getProviders()
    {
        return $this['providers'] ?? [];
    }
    public function getId()
    {
        return $this['id'];
    }
    public function getName()
    {
        return $this['name'] ?? '';
    }
    public function getTitle()
    {
        return $this['title'] ?? $this['name'];
    }
    public function getBaseType()
    {
        return $this['baseType'];
    }
    public function getDescription()
    {
        return $this['description'];
    }
    public function getVersion()
    {
        return $this['version'];
    }

    protected function getKeyOption($key)
    {
        return trim(Str::lower("option_datainfo_" . $this['baseType'] . '_' . $this->getId() . '_' . $key . '_value'));
    }
    public function getOption($key, $default = null)
    {
        return setting($this->getKeyOption($key), $default);
    }
    public function setOption($key, $value)
    {
        return setSetting($this->getKeyOption($key), $value);
    }
    public function getStatusData()
    {
        return $this->getBase()->check($this->getId()) || $this['ACTIVE'];
    }

    public function getStudlyName()
    {
        return Str::studly($this->getName());
    }
    public function getLowerName()
    {
        return Str::lower($this->getName());
    }
    public function getNamespaceInfo()
    {
        return $this['namespace'];
    }
}
