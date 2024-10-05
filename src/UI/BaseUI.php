<?php

namespace Sokeio\UI;

class BaseUI
{
    private $childs = [];
    private $data = [];
    private $callbacks = [];
    private SoUI $manager;
    private BaseUI $parent;

    protected function __construct()
    {
        $this->initUI();
    }
    protected function initUI()
    {
        // TODO: Implement initUI() method.
    }
    public function getParent()
    {
        return $this->parent;
    }
    public function setParent(BaseUI $parent)
    {
        $this->parent = $parent;
        return $this;
    }
    public function setManager(SoUI $manager)
    {
        $this->manager = $manager;
        foreach ($this->childs as $child) {
            $child->setManager($manager);
        }
        return $this;
    }
    public function action($key, $callback)
    {
        $this->ready(function () use ($key, $callback) {
            $this->manager->action($key, $callback, $this);
        });
        return $this;
    }
    private function callbackWithKey($key, $callback)
    {
        if (!is_callable($callback)) {
            return $this;
        }
        if (!isset($this->callbacks[$key])) {
            $this->callbacks[$key] = [];
        }
        $this->callbacks[$key][] = $callback;
        return $this;
    }
    public function runCallbacks($key)
    {
        foreach ($this->childs as $child) {
            $child->runCallbacks($key);
        }
        if (!isset($this->callbacks[$key])) {
            return $this;
        }
        foreach ($this->callbacks[$key] as $callback) {
            // remove args first
            call_user_func($callback, $this, ...array_shift(func_get_args()));
        }
        return $this;
    }
    public function className($className)
    {
        return $this->attrAdd('class', $className);
    }
    public function id($id)
    {
        return $this->attr('id', $id);
    }
    public function getId()
    {
        return $this->getAttrKey('id');
    }
    public function vars($key, $value = null)
    {
        $value = is_array($value) ? $value : [$value];
        if ($this->data['vars'] ?? null) {
            $this->data['vars'][$key] = $value;
        } else {
            $this->data['vars'] = [$key => $value];
        }
        return $this;
    }
    public function getVar($key, $default = null)
    {
        if (!isset($this->data['vars'])) {
            $this->data['vars'] = [];
        }
        if (!isset($this->data['vars'][$key])) {
            return $default;
        }
        return trim(implode(' ', $this->data['vars'][$key]));
    }
    public function attr($key, $value = null)
    {
        $value = is_array($value) ? $value : [$value];
        if ($this->data['attributes'] ?? null) {
            $this->data['attributes'][$key] = $value;
        } else {
            $this->data['attributes'] = [$key => $value];
        }
        return $this;
    }
    public function attrAdd($key, $value = null)
    {
        $value = is_array($value) ? $value : [$value];
        if ($this->data['attributes'] ?? null) {
            $this->data['attributes'][$key] = array_merge($this->data['attributes'][$key] ?? [], $value);
        } else {
            $this->data['attributes'] = [$key => $value];
        }

        return $this;
    }
    protected function getAttrKey($key, $default = null)
    {
        return $this->data['attributes'][$key] ?? $default;
    }
    public function getAttr()
    {
        $attr = '';
        if (!isset($this->data['attributes'])) {
            $this->data['attributes'] = [];
        }
        foreach ($this->data['attributes'] as $key => $value) {
            if (is_array($value)) {
                $value = implode(' ', $value);
            }
            $attr .= ' ' . $key . '="' . htmlentities($value, ENT_QUOTES, 'UTF-8') . '"';
        }
        return $attr;
    }
    public function runReady()
    {
        return $this->runCallbacks('ready');
    }
    public function ready($callback)
    {
        return $this->callbackWithKey('ready', $callback);
    }
    public function runRegister()
    {
        return $this->runCallbacks('register');
    }
    public function register($callback)
    {
        return $this->callbackWithKey('register', $callback);
    }
    public function runBoot()
    {
        return $this->runCallbacks('boot');
    }
    public function boot($callback)
    {
        return $this->callbackWithKey('boot', $callback);
    }
    public function runRender()
    {
        return $this->runCallbacks('render')->view();
    }
    public function render($callback)
    {
        return $this->callbackWithKey('render', $callback);
    }
    public function view()
    {
        $attr = $this->getAttr();
        return <<<HTML
        <div {$attr}></div>
        HTML;
    }
    public function toArray()
    {
        $childs = [];
        foreach ($this->childs as $key => $child) {
            if (is_array($child)) {
                foreach ($child as $k => $c) {
                    $childs[$key][$k] = $c->toArray();
                }
            } else {
                $childs[$key] = $child->toArray();
            }
        }
        return [
            'ui' => self::class,
            'data' => ($this->data),
            'childs' => $childs
        ];
    }

    public static function toUI($arr)
    {
        if (!$arr) {
            return [];
        }
        [
            'ui' => $ui,
            'data' => $data,
            'childs' => $childs
        ] = $arr;
        return $ui::create($data, $childs);
    }

    public static function create($data, $childs = [])
    {
        $instance = new static();
        $instance->data = $data;
        if ($childs) {
            foreach ($childs as $key => $child) {
                if (is_array($child)) {
                    foreach ($child as $k => $c) {
                        $instance->childs[$key][$k] = self::toUI($c)->setParent($instance);
                    }
                } else {
                    $instance->childs[$key] = self::toUI($child)->setParent($instance);
                }
            }
        }

        return $instance;
    }
    public static function init()
    {
        return new static();
    }
}
