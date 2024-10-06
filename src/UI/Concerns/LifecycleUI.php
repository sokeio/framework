<?php

namespace Sokeio\UI\Concerns;

use Sokeio\UI\Support\HookUI;

trait LifecycleUI
{
    protected HookUI $hook;
    public function initLifecycleUI()
    {
        $this->hook = new HookUI();
    }
    private function lifecycleWithKey($key, $callback = null, $params = null): static
    {
        if ($callback) {
            $this->hook->group($key)->callback($callback);
        } else {
            if (count($params) > 1) {
                $this->hook->group($key)->run([$this, ...array_shift($params)]);
            } else {
                $this->hook->group($key)->run([$this]);
            }
        }
        return $this;
    }
    public function ready($callback = null)
    {
        return $this->lifecycleWithKey('ready', $callback, (func_get_args()));
    }
    public function register($callback = null)
    {
        return $this->lifecycleWithKey('register', $callback, (func_get_args()));
    }
    public function boot($callback = null)
    {
        return $this->lifecycleWithKey('boot', $callback, (func_get_args()));
    }
    public function render($callback = null)
    {
        return $this->lifecycleWithKey('render', $callback, (func_get_args()));
    }
}
