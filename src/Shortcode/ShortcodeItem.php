<?php

namespace BytePlatform\Shortcode;

use BytePlatform\Action;
use BytePlatform\BaseManager;
use BytePlatform\Item;
use Livewire\Livewire;

class ShortcodeItem extends BaseManager
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
        ])->View(function ($shortcode) {
            return $shortcode->namespace  . $shortcode->getKey();
        });
    }
    private $namespace = 'byte::shortcodes.';
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

    /** @var Shortcode[] $__parameters */
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

    public function ShortcodeSetting($ShortcodeSetting)
    {
        return $this->setKeyValue('ShortcodeSetting', $ShortcodeSetting);
    }

    public function getShortcodeSetting()
    {
        return $this->getValue('ShortcodeSetting');
    }

    public function StripTags($strip_tags = null)
    {
        if ($strip_tags == null) {
            $strip_tags = function () {
                return true;
            };
        }
        return $this->setKeyValue('strip_tags', $strip_tags);
    }

    public function getStripTags()
    {
        return $this->getValue('strip_tags');
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
    public function getClassContent()
    {
        if ($this->getColumn())
            return column_size($this->getColumn()) . " " . parent::getClassContent();
        return parent::getClassContent();
    }
    public function ActionData($ActionData)
    {
        return $this->setKeyValue('ActionData', $ActionData);
    }

    public function getActionData()
    {
        return $this->getValue('ActionData');
    }
    public function getShortcodeData()
    {
        $action =  $this->getActionData();
        if (is_string($action)) {
            if (Action::hasAction($action)) {
                return Action::CallActionWithParams($action, [
                    "params" => $this->getData(),
                    'shortcode' => $this
                ]);
            }
            if (($__aciton = app($action))) {
                return $__aciton->handleWithParams([
                    "params" => $this->getData(),
                    'shortcode' => $this
                ]);
            }
        }
        return $action;
    }
    public function renderHtml(ShortcodeInfo $shortcode, $content, ShortcodeManager $manager, $name, $viewData)
    {
        return Livewire::mount('byte::shortcode', [
            'shortcode' => $name,
            'content' => $content,
            'attrs' => $shortcode->toArray(),
        ]);
    }
}
