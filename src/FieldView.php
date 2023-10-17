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
    private const FIELD_DEFAULT = "text";
    /*
    * @var \BytePlatform\Forms\FieldView[] $fields
    */
    private static $fields = [];
    public static function RegisterField($field)
    {
        if (!$field) return;
        if (is_array($field)) {
            foreach ($field as $item) {
                self::RegisterField($item);
            }
            return;
        }
        self::$fields[$field->getFieldType()] = $field;
        self::macro($field->getFieldType(), function () use ($field) {
            return  $field->getFieldType();
        });
    }
    private static function GetFieldView($type, $default = self::FIELD_DEFAULT)
    {
        if (isset(self::$fields[$type])) return self::$fields[$type];
        if (isset(self::$fields[$default])) return self::$fields[$default];
        if (isset(self::$fields[self::FIELD_DEFAULT])) return self::$fields[self::FIELD_DEFAULT];
    }
    public static function Render(Item $item,  $itemForm = null, $dataId = null)
    {
        if ($item->getEdit()) {
            return view(self::GetFieldView($item->getType())->beforeRender($item)->getView(), [
                'item' => $item,
                'form' => $itemForm,
                'dataId' => $dataId
            ])->render();
        } else {
            return view(self::GetFieldView("readonly")->beforeRender($item)->getView(), [
                'item' => $item,
                'form' => $itemForm,
                'dataId' => $dataId
            ])->render();
        }
    }
}
