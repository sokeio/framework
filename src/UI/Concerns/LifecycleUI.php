<?php

namespace Sokeio\UI\Concerns;

use Sokeio\UI\BaseUI;
use Sokeio\UI\Support\HookUI;

trait LifecycleUI
{
    private $childs = [];
    private $params = [];
    private $prefix = '';
    private $group = '';
    protected $context = null;
    protected HookUI $hook;
    private $whenCallbacks = [];
    public function when($callback, $group = 'default')
    {
        if (!isset($this->whenCallbacks[$group])) {
            $this->whenCallbacks[$group] = [];
        }
        $this->whenCallbacks[$group][] = $callback;
        return $this;
    }
    public function checkWhen($group = 'default')
    {
        if (!isset($this->whenCallbacks[$group])) {
            return true;
        }
        foreach ($this->whenCallbacks[$group] as $callback) {
            if (!call_user_func($callback, $this)) {
                return false;
            }
        }
        return true;
    }
    public function initLifecycleUI()
    {
        $this->hook = new HookUI();
    }
    protected function setupChild($callback = null)
    {
        if (!$callback || !is_callable($callback) || empty($this->childs)) {
            return;
        }
        foreach ($this->childs as  $childs) {
            if (is_array($childs) && !empty($childs)) {
                foreach ($childs as $c) {
                    if ($c && is_subclass_of($c, BaseUI::class)) {
                        $callback($c);
                    }
                }
            }
        }
    }
    private function lifecycleWithKey($key, $callback = null, $params = null): static
    {
        if ($callback) {
            $this->hook->group($key)->callback($callback);
            return $this;
        }
        if ($params && count($params) > 1) {
            $this->hook->group($key)->run([$this, ...array_shift($params)]);
        } else {
            $this->hook->group($key)->run([$this]);
        }
        if ($key == 'render') {
            return $this;
        }
        $this->setupChild(fn($c) => $c->lifecycleWithKey($key));
        return $this;
    }
    public function setGroup($group)
    {
        $this->group = $group;
        $this->setupChild(fn($c) => $c->setGroup($group));
        return $this;
    }
    public function getGroup()
    {
        return $this->group;
    }
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        $this->setupChild(fn($c) => $c->setPrefix($prefix));
        return $this;
    }

    public function clearPrefix()
    {
        $this->prefix = '';
        $this->setupChild(fn($c) => $c->clearPrefix());
        return $this;
    }
    public function getPrefix()
    {
        return $this->prefix;
    }
    public function getContext()
    {
        return $this->context;
    }
    public function setContext($context)
    {
        $this->context = $context;
        $this->setupChild(fn($c) => $c->setContext($context));

        return $this;
    }
    public function clearContext()
    {
        $this->context = null;
        $this->setupChild((fn($c) => $c->clearContext()));
        return $this;
    }
    public function setParams($params)
    {
        $this->params = $params;
        $this->setupChild(fn($c) => $c->setParams($params));
        return $this;
    }

    public function clearParams()
    {
        $this->params = [];
        $this->setupChild(fn($c) => $c->clearParams());
        return $this;
    }
    public function getParams($key = null, $keyParam = null, $default = null)
    {
        if (!$key) {
            return $this->params;
        }
        if (!$keyParam) {
            return $this->params[$key] ?? $default;
        }
        return data_get($this->params[$key], $keyParam, $default);
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
    protected function child($childs = [], $group = 'default')
    {
        if (!$childs) {
            return $this;
        }
        if (!is_array($childs)) {
            $childs = [$childs];
        }
        foreach ($childs as  $child) {
            if ($child instanceof BaseUI) {
                $child->setGroup($group);
                $child->setParent($this);
            }
        }
        $this->childs[$group] = array_merge($this->childs[$group] ?? [], $childs);
        return $this;
    }
    protected function renderChilds($group = 'default', $params = null, $callback = null)
    {
        return $this->getManager()->getHtml($this->childs[$group] ?? [],  $params, $callback);
    }
    public function hasChilds($group = 'default')
    {
        return count($this->childs[$group] ?? []) > 0;
    }
    public function tap($callback)
    {
        $callback($this);

        return $this;
    }
}
