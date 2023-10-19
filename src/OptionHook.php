<?php

namespace BytePlatform;

use Illuminate\Support\Traits\Macroable;

class OptionHook
{
    private function __construct()
    {
    }
    use Macroable;
    private $callbacks = [];
    public function Active($callback)
    {
        $this->callbacks['active'] = $callback;
        return $this;
    }
    public function UnActive($callback)
    {
        $this->callbacks['unactive'] = $callback;
        return $this;
    }
    public function Options($callback)
    {
        $this->callbacks['option'] = $callback;
        return $this;
    }
    private $cacheOptions = null;
    public function getOptions($dataInfo): FormCollection
    {
        if ($this->cacheOptions) return $this->cacheOptions;
        if (isset($this->callbacks['option']) && is_callable($this->callbacks['option'])) {
            return ($this->cacheOptions = $this->callbacks['option']($dataInfo));
        }
        return ($this->cacheOptions = FormCollection::Create());
    }
    public function changeStatus($dataInfo, $status)
    {
        if ($status == 1 && isset($this->callbacks['active']) && is_callable($this->callbacks['active'])) {
            $this->callbacks['active']($dataInfo);
            return;
        }
        if ($status == 0 && isset($this->callbacks['unactive']) && is_callable($this->callbacks['unactive'])) {
            $this->callbacks['unactive']($dataInfo);
            return;
        }
    }
    public static function Create()
    {
        return new static();
    }
}
