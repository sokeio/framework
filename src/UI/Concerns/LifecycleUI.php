<?php

namespace Sokeio\UI\Concerns;

use Sokeio\UI\BaseUI;
use Sokeio\UI\Support\HookUI;

trait LifecycleUI
{
    private $childs = [];
    private $params = [];
    private $prefix = '';
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
        foreach ($this->childs as  $childs) {
            if (is_array($childs)) {
                foreach ($childs as $c) {
                    if (is_subclass_of($c, BaseUI::class)) {
                        $c->lifecycleWithKey($key);
                    }
                }
            }
        }
        return $this;
    }
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        foreach ($this->childs as  $childs) {
            if (is_array($childs)) {
                foreach ($childs as $c) {
                    if (is_subclass_of($c, BaseUI::class)) {
                        $c->setPrefix($prefix);
                    }
                }
            }
        }
        return $this;
    }

    public function clearPrefix()
    {
        $this->prefix = '';
        foreach ($this->childs as  $childs) {
            if (is_array($childs)) {
                foreach ($childs as $c) {
                    if (is_subclass_of($c, BaseUI::class)) {
                        $c->clearPrefix();
                    }
                }
            }
        }
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
        foreach ($this->childs as  $childs) {
            if (is_array($childs)) {
                foreach ($childs as $c) {
                    if (is_subclass_of($c, BaseUI::class)) {
                        $c->setContext($context);
                    }
                }
            }
        }
        return $this;
    }
    public function clearContext()
    {
        $this->context = null;
        foreach ($this->childs as  $childs) {
            if (is_array($childs)) {
                foreach ($childs as $c) {
                    if (is_subclass_of($c, BaseUI::class)) {
                        $c->clearContext();
                    }
                }
            }
        }
        return $this;
    }
    public function setParams($params)
    {
        $this->params = $params;
        foreach ($this->childs as  $childs) {
            if (is_array($childs)) {
                foreach ($childs as $c) {
                    if (is_subclass_of($c, BaseUI::class)) {
                        $c->setParams($params);
                    }
                }
            }
        }
        return $this;
    }

    public function clearParams()
    {
        $this->params = [];
        foreach ($this->childs as  $childs) {
            if (is_array($childs)) {
                foreach ($childs as $c) {
                    if (is_subclass_of($c, BaseUI::class)) {
                        $c->clearParams();
                    }
                }
            }
        }
        return $this;
    }
    public function getParams($key = null)
    {
        if ($key) {
            return $this->params[$key] ?? null;
        }
        return $this->params;
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
        foreach ($childs as  $child) {
            if ($child instanceof BaseUI) {
                $child->setParent($this);
            }
        }
        $this->childs[$group] = array_merge($this->childs[$group] ?? [], $childs);
        return $this;
    }
    protected function renderChilds($group = 'default', $params = null)
    {
        $html = '';
        foreach ($this->childs[$group] ?? [] as $child) {
            if ($child instanceof BaseUI) {
                $child->setParams($params);
                $child->render();
                if ($child->checkWhen()) {
                    $html .= $child->view();
                }
                $child->clearParams();
            } elseif (is_array($child)) {
                $html .= implode('', $child);
            } elseif (is_callable($child)) {
                $html .= call_user_func($child, $this);
            } else {
                $html .= $child;
            }
        }

        return $html;
    }
    public function hasChilds($group = 'default')
    {
        return count($this->childs[$group] ?? []) > 0;
    }
}
