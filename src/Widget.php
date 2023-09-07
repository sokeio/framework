<?php

namespace BytePlatform;

use BytePlatform\Item;

class Widget extends BaseManager
{
    public static function Create($name)
    {
        return new self($name);
    }
    private function __construct($name)
    {
        parent::__construct();
        $this->Key($name)->Name(str($name)->replace('-', ' ')->ucfirst())->Parameters([
            Item::Add('poll')->Title('Polling')->Column(Item::Col3)->Type('select')->DataOption(function () {
                return Item::getPolls();
            }),
            Item::Add('column')->Title('Column')->Column(Item::Col3)->Type('select')->DataOption(function () {
                return Item::getColumnValue();
            }),
            Item::Add('title')->Column(Item::Col6)->Title('Title')
        ])->View(function ($widget) {
            return $widget->namespace  . $widget->getKey();
        });
    }
    private $namespace = 'byte::widgets.';
    private $callbackBeforeSetup = null;
    public function beforeRender($selfThis = null)
    {
        if ($this->callbackBeforeSetup) {
            if (is_callable($this->callbackBeforeSetup)) ($this->callbackBeforeSetup)($selfThis, $this);
            else if (is_string($this->callbackBeforeSetup) && $mid = app($this->callbackBeforeSetup)) {
                if (method_exists($mid, 'handle')) {
                    call_user_func([$mid, 'handle'], $selfThis, $this);
                }
            }
        }
        return $this;
    }
    public function triggerBefore($callback)
    {
        $this->callbackBeforeSetup = $callback;
        return $this;
    }
    public function NameSpace($name)
    {
        $this->namespace = $name;
        return $this;
    }

    /** @var Widget[] $__parameters */
    private  $__parameters = [];
    public function Parameters($parameters)
    {
        if (!$parameters) return $this;
        if (is_array($parameters)) {
            foreach ($parameters as $_parameters) {
                $this->Parameters($_parameters);
            }
            return $this;
        }
        if (is_callable($parameters)) {
            $parameters = $parameters($this);
        }
        if (is_string($parameters)) {
            $parameters = app($parameters);
        }
        $this->__parameters[] = $parameters->Manager($this);
        return $this;
    }
    public function getItems()
    {
        return $this->__parameters;
    }
    public function View($view)
    {
        return $this->setKeyValue('view', $view);
    }

    public function getView()
    {
        return $this->getValue('view');
    }
    public function Key($Key)
    {
        return $this->setKeyValue('Key', $Key);
    }

    public function getKey()
    {
        return $this->getValue('Key');
    }
    public function Name($Name)
    {
        return $this->setKeyValue('Name', $Name);
    }

    public function getName()
    {
        return $this->getValue('Name');
    }

    public function WidgetSetting($WidgetSetting)
    {
        return $this->setKeyValue('WidgetSetting', $WidgetSetting);
    }

    public function getWidgetSetting()
    {
        return $this->getValue('WidgetSetting');
    }

    public function Column($column)
    {
        return $this->setKeyValue('column', $column);
    }

    public function getColumn()
    {
        return $this->getValue('column');
    }

    public function getColumnSize()
    {
        return Item::getSize($this->getColumn());
    }
    public function ActionData($ActionData)
    {
        return $this->setKeyValue('ActionData', $ActionData);
    }

    public function getActionData()
    {
        return $this->getValue('ActionData');
    }
    public function getWidgetData()
    {
        $action =  $this->getActionData();
        if (is_string($action)) {
            if (Action::hasAction($action)) {
                return Action::CallActionWithParams($action, [
                    "params" => $this->getData(),
                    'widget' => $this
                ]);
            }
            if (($__aciton = app($action))) {
                return $__aciton->handleWithParams([
                    "params" => $this->getData(),
                    'widget' => $this
                ]);
            }
        }
        return $action;
    }
}
