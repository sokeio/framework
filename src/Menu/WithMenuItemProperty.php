<?php

namespace Sokeio\Menu;

trait WithMenuItemProperty
{


    public const KEY_CONTENT_TYPE = 'KEY_CONTENT_TYPE';
    public const KEY_CONTENT_COLOR = 'KEY_CONTENT_COLOR';
    public const KEY_CONTENT_DATA = 'KEY_CONTENT_DATA';
    public const KEY_CLASS_NAME = 'KEY_CLASS_NAME';
    public const KEY_TYPE = 'KEY_TYPE';
    public const KEY_TEXT = 'KEY_TEXT';
    public const KEY_INFO = 'KEY_INFO';
    public const KEY_ICON = 'KEY_ICON';
    public const KEY_ATTRIBUTE = 'KEY_ATTRIBUTE';
    public const KEY_SORT = 'KEY_SORT';
    public const KEY_TAG = 'KEY_TAG';
    public const KEY_LINK = 'KEY_LINK';
    public const KEY_DATA = 'KEY_DATA';
    public const KEY_CALLBACK = 'KEY_CALLBACK';
    public const KEY_BADGE = 'KEY_BADGE';
    public const KEY_PERMISSION = 'KEY_PERMISSION';
    public function getValueClassName()
    {
        return $this->getValue(self::KEY_CLASS_NAME);
    }

    public function setValueClassName($value, $default = null)
    {
        return $this->setValue(self::KEY_CLASS_NAME, $value, $default);
    }
    public function getValueContentData()
    {
        return $this->getValue(self::KEY_CONTENT_DATA);
    }

    public function setValueContentData($value, $default = null)
    {
        return $this->setValue(self::KEY_CONTENT_DATA, $value, $default);
    }
    public function getValueContentType()
    {
        return $this->getValue(self::KEY_CONTENT_TYPE);
    }
    public function setValueContentType($value, $default = null)
    {
        return $this->setValue(self::KEY_CONTENT_TYPE, $value, $default);
    }
    public function getValueInfo()
    {
        return $this->getValue(self::KEY_INFO);
    }
    public function setValueInfo($value, $default = null)
    {
        return $this->setValue(self::KEY_INFO, $value, $default);
    }
    public function getValueContentColor()
    {
        return $this->getValue(self::KEY_CONTENT_COLOR);
    }
    public function setValueContentColor($value, $default = null)
    {
        return $this->setValue(self::KEY_CONTENT_COLOR, $value, $default);
    }
    public function getValueType()
    {
        return $this->getValue(self::KEY_TYPE);
    }
    public function getPermission()
    {
        return $this->getValue(self::KEY_PERMISSION);
    }
    public function getValueText()
    {
        return $this->getValue(self::KEY_TEXT);
    }
    public function getValueIcon()
    {
        return $this->getValue(self::KEY_ICON);
    }
    public function getValueAttribute()
    {
        return $this->getValue(self::KEY_ATTRIBUTE);
    }
    public function getValueSort()
    {
        return $this->getValue(self::KEY_SORT);
    }
    public function getValueTag()
    {
        return $this->getValue(self::KEY_TAG);
    }
    public function getValueLink()
    {
        return $this->getValue(self::KEY_LINK);
    }
    public function setValueLink($value, $default = null)
    {
        return $this->setValue(self::KEY_LINK, $value, $default);
    }
    public function getValueData()
    {
        return $this->getValue(self::KEY_DATA);
    }
    public function getValueCallback()
    {
        return $this->getValue(self::KEY_CALLBACK);
    }
}
