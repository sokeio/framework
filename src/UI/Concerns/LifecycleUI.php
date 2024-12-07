<?php

namespace Sokeio\UI\Concerns;

use Illuminate\Support\Collection;
use Sokeio\Pattern\Tap;
use Sokeio\UI\BaseUI;
use Sokeio\UI\SoUI;
use Sokeio\UI\Support\HookUI;
use Sokeio\Enums\AlertPosition;
use Sokeio\Enums\AlertType;
use Sokeio\UI\Common\None;

trait LifecycleUI
{
    use Tap, DataShareUI;
    private BaseUI|SoUI|null  $parent = null;
    private $childs = [];
    protected HookUI $hook;
    private $whenCallbacks = [];
    private $skipBoot = false;
    private $uiId = null;
    private $uiIdKey = null;
    private $hookStatus = [];

    public function uiId($uiId)
    {
        $this->uiId = $uiId;
        return $this;
    }
    public function getUIIDkey()
    {
        if ($this->uiIdKey) {
            return $this->uiIdKey;
        }
        if ($this->parent) {
            return  $this->uiIdKey = md5($this->parent->getUIIDkey() . '_' . $this->getUIID());
        }
        return $this->uiIdKey = md5($this->getUIID());
    }
    public function getUIID()
    {
        return $this->uiId;
    }
    public function skipBoot()
    {
        $this->skipBoot = true;
        return $this;
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
        $this->callbackUI($this->childs, fn($c) => $callback($c));

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
        SoUI::checkDebug($this, $key);
        $this->hookStatus[$key] = true;
        if ($key == 'render') {
            return $this;
        }
        return  $this->setupChild(fn($c) => $c->lifecycleWithKey($key));
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
    private function callbackUI($uis, $callback = null)
    {
        foreach ($uis as $key => $child) {
            if ($child instanceof BaseUI) {
                $callback($child, $key);
            }
            if (is_array($child)) {
                $this->callbackUI($child, $callback);
            }
        }
        return $this;
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
        $this->callbackUI($childs, fn($c) => $c->setParent($this)->group($group));
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
        return $this->child(clone None::init(SoUI::getUI($key))->tap($tap), $group);
    }
    public function childWithGroupKey($key,  $tap = null, $group = 'default')
    {
        return $this->child(clone None::init(SoUI::getGroupUI($key))->tap($tap), $group);
    }
    private function getHtmlItem($ui, $params = null, $callback = null)
    {
        $html = '';
        if (is_array($ui)) {
            $html = $this->getHtmlItems($ui, $params, $callback);
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
    private function getHtmlItems($uis, $params = null, $callback = null)
    {
        $html = '';
        foreach ($uis as $ui) {
            $html .= $this->getHtmlItem($ui, $params, $callback);
        }
        return $html;
    }
    public function renderChilds($group = 'default', $params = null, $callback = null)
    {
        return $this->getHtmlItems($this->childs[$group] ?? [], $params, $callback);
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
        return $this;
    }
}
