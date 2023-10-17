<?php

namespace BytePlatform;

use Illuminate\Support\Traits\Macroable;

class FieldView
{
    use Macroable;
    private $namespace = 'byte::fields.';
    private $fieldType = 'text';
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
    public function Namespace($name)
    {
        $this->namespace = $name;
        return $this;
    }
    public function FieldType($name)
    {
        $this->fieldType = $name;
        return $this;
    }
    public function getFieldType()
    {
        return $this->fieldType;
    }
    public function getView()
    {
        return $this->namespace . $this->fieldType;
    }
    public static function Create($name): self
    {
        $self = new self();
        return $self->FieldType($name);
    }
}
