<?php

namespace Sokeio\UI\Concerns;

use Sokeio\UI\BaseUI;
use Sokeio\UI\Support\HookUI;

trait LifecycleUI
{
    private $childs = [];
    private $params = [];
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
                        if ($c instanceof BaseUI) {
                            $c->lifecycleWithKey($key);
                        }
                    }
                }
            }
        }
        return $this;
    }
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }
    public function clearParams()
    {
        $this->params = [];
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
                $html .= $child->view();
                $child->clearParams();
            } elseif (is_string($child)) {
                $html .= $child;
            } elseif (is_array($child)) {
                $html .= implode('', $child);
            } elseif (is_callable($child)) {
                $html .= call_user_func($child, $this);
            }
        }

        return $html;
    }
    public function hasChilds($group = 'default')
    {
        return count($this->childs[$group] ?? []) > 0;
    }
}
