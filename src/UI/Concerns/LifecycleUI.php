<?php

namespace Sokeio\UI\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Drawer\Utils;
use Sokeio\Pattern\Tap;
use Sokeio\UI\BaseUI;
use Sokeio\UI\SoUI;
use Sokeio\UI\Support\HookUI;

trait LifecycleUI
{
    use Tap;
    private BaseUI|SoUI  $parent;
    private $showLogDebug = false;
    private $childs = [];
    private $params = [];
    private $prefix = '';
    private $group = '';
    protected $context = null;
    protected $viewFactory = null;
    protected HookUI $hook;
    private $whenCallbacks = [];
    private $callbackDebug = null;
    private $skipBoot = false;
    private $uiId = null;
    public function uiId($uiId)
    {
        $this->uiId = $uiId;
        return $this;
    }
    public function skipBoot()
    {
        $this->skipBoot = true;
        return $this;
    }
    public function debug($callback)
    {
        $this->callbackDebug = $callback;
        return $this;
    }
    private function checkDebug($key)
    {
        if ($this->showLogDebug) {
            if ($this->uiId == null) {
                $this->uiId = uniqid();
            }
            Log::info([$this->uiId, static::class, $key, $this->viewFactory ? "true" : "false"]);
        }
        if ($this->callbackDebug) {
            call_user_func($this->callbackDebug, $this, $key);
        }
    }
    public function getParent()
    {
        return $this->parent;
    }
    public function setParent(BaseUI|SoUI $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function debounce($debounce = 250)
    {
        return $this->vars('wire:debounce', $debounce)->setupChild(fn($c) => $c->debounce($debounce));
    }
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
            return $this;
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

        return $this;
    }
    public function lifecycleWithKey($key, $callback = null, $params = null): static
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
        $this->checkDebug($key);
        if ($key == 'render') {
            return $this;
        }
        return  $this->setupChild(fn($c) => $c->lifecycleWithKey($key));
    }
    public function setGroup($group)
    {
        if ($this->group) {
            return $this;
        }
        $this->group = $group;
        $this->checkDebug('setGroup');
        return $this->setupChild(fn($c) => $c->setGroup($group));
    }
    public function getGroup()
    {
        return $this->group;
    }
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        $this->checkDebug('setPrefix');
        return $this->setupChild(fn($c) => $c->setPrefix($prefix));
    }

    public function clearPrefix()
    {
        $this->prefix = '';
        return $this->setupChild(fn($c) => $c->clearPrefix());
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
        $this->checkDebug('setContext');
        return $this->setupChild(fn($c) => $c->setContext($context));
    }
    public function clearContext()
    {
        $this->context = null;
        return $this->setupChild((fn($c) => $c->clearContext()));
    }
    public function getViewFactory()
    {
        $this->checkDebug('getViewFactory');
        return $this->viewFactory;
    }
    public function makeView($view, $data = [], $mergeData = [])
    {
        return $this->getViewFactory()->make($view, $data, $mergeData)
            ->with(Utils::getPublicPropertiesDefinedOnSubclass($this->getWire()));
    }
    public function viewRender($view, $data = [], $mergeData = [])
    {
        return $this->makeView($view, $data, $mergeData)->render();
    }

    public function setViewFactory($viewFactory)
    {
        $this->viewFactory = $viewFactory;
        $this->checkDebug('setViewFactory');
        return $this->setupChild(fn($c) => $c->setViewFactory($viewFactory));
    }
    public function clearViewFactory()
    {
        $this->viewFactory = null;
        return $this->setupChild((fn($c) => $c->clearViewFactory()));
    }

    public function setParams($params)
    {
        if (!is_array($params)) {
            $params = [$params];
        }
        $this->params = array_merge($this->params, $params);
        $this->checkDebug('setParams');
        return $this->setupChild(fn($c) => $c->setParams($params));
    }

    public function clearParams()
    {
        $this->params = [];
        return $this->setupChild(fn($c) => $c->clearParams());
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
    public function bootAction($callback = null)
    {
        return $this->lifecycleWithKey('bootAction', $callback, (func_get_args()));
    }
    public function boot($callback = null)
    {
        if ($callback == null && $this->skipBoot) {
            $this->skipBoot = false;
            return $this;
        }
        return $this->lifecycleWithKey('boot', $callback, (func_get_args()));
    }
    public function render($callback = null)
    {
        return $this->lifecycleWithKey('render', $callback, (func_get_args()));
    }
    public function child($childs = [], $group = 'default')
    {
        if (!$childs) {
            return $this;
        }
        if ($childs instanceof Collection) {
            $childs = $childs->toArray();
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
        $this->childs[$group] = array_merge($this->childs[$group] ?? [],  $childs);
        return $this;
    }
    private function getHtmlItem($ui, $params = null, $callback = null)
    {
        $html = '';
        if (is_array($ui)) {
            $html = implode('', $ui);
        }
        if (is_callable($ui)) {
            $html = call_user_func($ui);
        }
        if ($ui instanceof BaseUI) {
            $rs = null;
            if ($callback) {
                $rs = call_user_func($callback, $ui);
            }
            $ui->setParams($params);
            $ui->render();
            if ($ui->checkWhen()) {
                $html = $ui->view();
            }
            $ui->clearParams();
            if ($rs && is_callable($rs)) {
                call_user_func($rs, $ui);
            }
        }

        return $html;
    }
    protected function renderChilds($group = 'default', $params = null, $callback = null)
    {
        $html = '';
        foreach ($this->childs[$group] ?? [] as $child) {
            $html .= $this->getHtmlItem($child, $params, $callback);
        }
        return $html;
    }
    public function hasChilds($group = 'default')
    {
        return count($this->childs[$group] ?? []) > 0;
    }
}
