<?php

namespace Sokeio\Concerns;

use Illuminate\Support\Traits\Macroable;

trait WithCallback
{
    use Macroable;
    private $callbackData = [];
    private $callbackDataCache = [];
    private $callbackDisableCache = false;
    public function disableCache()
    {
        $this->callbackDisableCache = true;
        return $this;
    }
    public function enableCache()
    {
        $this->callbackDisableCache = false;
        return $this;
    }
    public function clear()
    {
        $this->callbackData = [];
        $this->callbackDataCache = [];
        return $this;
    }
    public function clearCache()
    {
        $this->callbackDataCache = [];
        return $this;
    }
    private $manager;
    public function manager($manager)
    {
        $this->manager = $manager;
        return $this;
    }
    public function getManager()
    {
        return $this->manager ?? $this;
    }
    protected function getValueByCallback($valueOrCallback)
    {
        if ($valueOrCallback && !is_string($valueOrCallback) && is_callable($valueOrCallback)) {
            return  $valueOrCallback($this, $this->getManager());
        }
        if ($valueOrCallback && is_object($valueOrCallback) && method_exists($valueOrCallback, 'manager')) {
            $valueOrCallback->manager($this->getManager());
        }
        return $valueOrCallback;
    }
    protected function checkKey($__key)
    {
        return  isset($this->callbackData[$__key]);
    }
    protected function getValue($__key, $__default = null, $withoutCache = false)
    {
        if (!$this->callbackDisableCache && !$withoutCache && isset($this->callbackDataCache[$__key])) {
            return $this->callbackDataCache[$__key];
        }
        $valueOrCallback = $this->checkKey($__key) ? $this->callbackData[$__key] : $__default;
        $this->callbackDataCache[$__key] = ($this->getValueByCallback($valueOrCallback) ?? $__default);
        return $this->callbackDataCache[$__key];
    }
    protected function setKeyValue($__key, $value, $safeKey = false)
    {
        if (!isset($this->callbackData[$__key]) ||  !$safeKey) {
            $this->callbackData[$__key] = $value;
            if (isset($this->callbackDataCache[$__key])) {
                unset($this->callbackDataCache[$__key]);
            }
        }
        return $this;
    }
}
