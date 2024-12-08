<?php

namespace Sokeio\UI\Concerns;

use Sokeio\UI\BaseUI;
use Sokeio\UI\SoUI;
use Sokeio\UI\Support\HookUI;

trait LifecycleUI
{
    use CoreUI;
    private $childs = [];
    protected HookUI $hook;
    private $hookStatus = [];

    // 1. register UI
    public function register($callback = null)
    {
        return $this->lifecycleWithKey('register', $callback, func_get_args());
    }
    // 2. boot UI
    public function boot($callback = null)
    {
        return $this->lifecycleWithKey('boot', $callback, func_get_args());
    }
    // 3. beforeRender UI
    public function beforeRender($callback = null)
    {
        return $this->lifecycleWithKey('beforeRender', $callback, func_get_args());
    }
    // 4. render UI
    public function render($callback = null)
    {
        return $this->lifecycleWithKey('render', $callback, func_get_args());
    }
    // 5. afterRender UI
    public function afterRender($callback = null)
    {
        return $this->lifecycleWithKey('afterRender', $callback, func_get_args());
    }
    // 6. destroy UI
    public function destroy($callback = null)
    {
        return $this->lifecycleWithKey('destroy', $callback, func_get_args());
    }
    public function debounce($debounce = 250)
    {
        return $this->vars('wire:debounce', $debounce)->setupChild(fn($c) => $c->debounce($debounce));
    }


    public function initLifecycleUI()
    {
        $this->hook = new HookUI();
    }

    public function lifecycleWithKey($key, $callback = null, $params = null): static
    {
        if ($callback && !isset($this->hookStatus[$key])) {
            $this->hook->group($key)->callback($callback);
            return $this;
        }
        if ($params && count($params) > 1) {
            $this->hook->group($key)->run([$this, ...array_shift($params)]);
        } else {
            $this->hook->group($key)->run([$this]);
        }
        SoUI::checkDebug($this, $key);
        $this->hookStatus[$key] = true;
        if ($key == 'render') {
            return $this;
        }
        return $this->setupChild(fn($c) => $c->lifecycleWithKey($key));
    }
}
