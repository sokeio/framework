<?php

namespace Sokeio\UI\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Drawer\Utils;
use Sokeio\Pattern\Tap;
use Sokeio\UI\BaseUI;
use Sokeio\UI\SoUI;
use Sokeio\UI\Support\HookUI;
use Sokeio\Enums\AlertPosition;
use Sokeio\Enums\AlertType;
use Sokeio\UI\Common\None;

trait LifecycleUI
{
    use Tap;
    private BaseUI|SoUI|null  $parent = null;
    private SoUI|null $manager = null;
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
    private $hookStatus = [];
    public function getManager()
    {
        return $this->manager;
    }
    public function registerManager(SoUI $manager)
    {
        $this->manager = $manager;
        $this->setupChild(fn($c) => $c->registerManager($manager));
        return $this;
    }
    public function uiId($uiId)
    {
        $this->uiId = $uiId;
        return $this;
    }
    public function getUIIDkey()
    {
        if ($this->parent) {
            return $this->parent->getUIIDkey() . '_' . $this->getUIID();
        }
        return $this->getUIID();
    }
    public function getUIID()
    {
        return $this->uiId;
    }
    private $groupField = null;
    public function groupField($group)
    {
        $this->groupField = $group;
        return $this;
    }
    public function getGroupField()
    {
        if ($this->groupField) {
            return $this->groupField;
        }
        return $this->getParent()?->getGroupField();
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
        if ($callback && !isset($this->hookStatus[$key])) {
            $this->hook->group($key)->callback($callback);
            return $this;
        }
        if ($params && count($params) > 1) {
            $this->hook->group($key)->run([$this, ...array_shift($params)]);
        } else {
            $this->hook->group($key)->run([$this]);
        }
        $this->hookStatus[$key] = true;
        $this->checkDebug($key);
        if ($key == 'render') {
            return $this;
        }
        return  $this->setupChild(fn($c) => $c->lifecycleWithKey($key));
    }
    public function setGroup($group)
    {
        $this->group = $group;
        return $this;
    }
    public function getGroup()
    {
        if ($this->group) {
            return $this->group;
        }
        return $this->parent ? $this->parent->getGroup() : '';
    }
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    public function clearPrefix()
    {
        $this->prefix = '';
        return $this;
    }
    public function getPrefix()
    {
        if ($this->prefix) {
            return $this->prefix;
        }
        return $this->parent ? $this->parent->getPrefix() : '';
    }
    public function getContext()
    {
        if ($this->context) {
            return $this->context;
        }
        return $this->parent ? $this->parent->getContext() : null;
    }
    public function setContext($context)
    {
        $this->context = $context;
        return $this;
    }
    public function clearContext()
    {
        $this->context = null;
        return $this;
    }
    public function getViewFactory()
    {
        if ($this->viewFactory) {
            return $this->viewFactory;
        }
        return $this->parent ? $this->parent->getViewFactory() : null;
    }
    public function makeView($view, $data = [], $mergeData = [])
    {
        return $this->getViewFactory()?->make($view, $data, $mergeData)
            ->with([...Utils::getPublicPropertiesDefinedOnSubclass($this->getWire()), 'soUI' => $this]);
    }
    public function viewRender($view, $data = [], $mergeData = [])
    {
        return $this->makeView($view, $data, $mergeData)->render();
    }

    public function setViewFactory($viewFactory)
    {
        $this->viewFactory = $viewFactory;
        return $this;
    }
    public function clearViewFactory()
    {
        $this->viewFactory = null;
        return $this;
    }

    public function setParams($params)
    {
        if (!is_array($params)) {
            $params = [$params];
        }
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    public function clearParams()
    {
        $this->params = [];
        return $this;
    }
    public function getParams($key = null, $keyParam = null, $default = null)
    {
        if (!$this->params) {
            return $this->parent ? $this->parent->getParams($key, $keyParam, $default) : $default;
        }
        $value = $this->params;
        if ($keyParam && $key) {
            $value =  data_get($this->params[$key], $keyParam, $default);
        }
        if (!$keyParam && $key) {
            $value = $this->params[$key] ?? $default;
        }
        return $value;
    }
    public function ready($callback = null)
    {
        return $this->lifecycleWithKey('ready', $callback, (func_get_args()));
    }
    public function register($callback = null)
    {
        return $this->lifecycleWithKey('register', $callback, (func_get_args()));
    }
    public function beforeBoot($callback = null)
    {
        return $this->lifecycleWithKey('beforeBoot', $callback, (func_get_args()));
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
    public function renderAction($callback = null)
    {
        return $this->lifecycleWithKey('renderAction', $callback, (func_get_args()));
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
        foreach ($this->childs[$group] as $key => $child) {
            if ($child instanceof BaseUI) {
                $child->uiId(str($group . '-' . $key)->replace('.', '-')->replace('-', '_'));
            }
        }
        return $this;
    }
    public function childWithKey($key,  $tap = null, $group = 'default')
    {
        return $this->child(None::init(SoUI::getUI($key))->tap($tap), $group);
    }
    public function childWithGroupKey($key,  $tap = null, $group = 'default')
    {
        return $this->child(None::init(SoUI::getGroupUI($key))->tap($tap), $group);
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
            $ui->renderAction();
            $ui->render();
            if ($ui->checkWhen()) {
                $html = $ui->view();
            }
            $ui->clearParams();
            if ($rs && is_callable($rs)) {
                call_user_func($rs, $ui);
            }
        } elseif (is_string($ui)) {
            $html = $ui;
        }

        return $html;
    }
    public function renderChilds($group = 'default', $params = null, $callback = null)
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

    public function wireAlert(
        $message,
        $title = null,
        $messageType = AlertType::SUCCESS,
        $position = AlertPosition::TOP_CENTER,
        $timeout = 5000
    ) {
        $this->getWire()->alert($message, $title, $messageType, $position, $timeout);
    }
}
