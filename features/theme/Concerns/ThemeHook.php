<?php

namespace Sokeio\Theme\Concerns;

trait ThemeHook
{

    private $hook = [];
    protected function hook($name, $callback)
    {
        if (!$callback || !is_callable($callback)) {
            return $this;
        }
        if (!isset($this->hook[$name])) {
            $this->hook[$name] = [];
        }
        $this->hook[$name][] = $callback;
        return $this;
    }
    public function callhook($name, $args = [])
    {
        if (!isset($this->hook[$name])) {
            return;
        }
        foreach ($this->hook[$name] as $callback) {
            call_user_func_array($callback, $args);
        }
    }
    public function bodyBefore($callback)
    {
        return $this->hook('bodyBefore', $callback);
    }
    public function bodyAfter($callback)
    {
        return $this->hook('bodyAfter', $callback);
    }
    public function bodyAttr($callback)
    {
        return $this->hook('bodyAttr', $callback);
    }
    public function headBefore($callback)
    {
        return $this->hook('headBefore', $callback);
    }
    public function headAfter($callback)
    {
        return $this->hook('headAfter', $callback);
    }
}
