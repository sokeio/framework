<?php

namespace Sokeio\UI\Concerns;

use Illuminate\Support\Facades\Log;
use Livewire\Drawer\Utils;
use Sokeio\Enums\AlertPosition;
use Sokeio\Enums\AlertType;
use Sokeio\Pattern\Tap;
use Sokeio\UI\BaseUI;
use Sokeio\UI\SoUI;

trait CoreUI
{
    use Tap, CoreChildUI;
    private $uiId = null;
    private $uiIdKey = null;
    private $uiGroup = null;
    private SoUI|null $manager = null;
    private BaseUI|SoUI|null  $parent = null;
    private $whenCallbacks = [];
    public function clearCoreUI()
    {
        $this->uiId = null;
        $this->uiIdKey = null;
        $this->uiGroup = null;
        $this->manager = null;
        $this->parent = null;
    }
    public function getWire()
    {
        return $this->getManager()?->getWire();
    }
    public function resetErrorBag($field = null)
    {
        if ($field) {
            $field = $this->getNameWithPrefix($field);
        }
        $this->getWire()->resetErrorBag($field);
        return $this;
    }
    public function addError($field, $message)
    {
        $this->getWire()->addError($this->getNameWithPrefix($field), $message);
        return $this;
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
        if ($this->getParent()) {
            return  $this->uiIdKey = md5($this->getParent()->getUIIDkey() . '_' . $this->getUIID());
        }
        return $this->uiIdKey = md5($this->getUIID());
    }
    public function getUIID()
    {
        return $this->uiId;
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

    public function getManager(): SoUI|null
    {
        return $this->manager;
    }
    public function setManager(SoUI $manager)
    {
        $this->manager = $manager;
        $this->setupChild(fn($c) => $c->setManager($manager));
        return $this;
    }
    public function group($value)
    {
        $this->uiGroup = $value;
        return $this;
    }
    public function getGroup($default = null)
    {
        return $this->uiGroup ?? $default;
    }
    public function makeView($view, $data = [], $mergeData = [])
    {
        return $this->getManager()?->getViewFactory()?->make($view, $data, $mergeData)
            ->with([...Utils::getPublicPropertiesDefinedOnSubclass($this->getWire()), 'soUI' => $this]);
    }
    public function viewRender($view, $data = [], $mergeData = [])
    {
        return $this->makeView($view, $data, $mergeData)->render();
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
}
