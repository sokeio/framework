<?php

namespace Sokeio\UI\Field\Concerns;

use Sokeio\Setting;

trait WithFieldInSetting
{
    private $__loadInSettingCallback;
    private $__saveInSettingCallback;

    public function loadInSetting($callback = null)
    {
        if ($callback) {
            $this->__loadInSettingCallback = $callback;
        } elseif ($this->__loadInSettingCallback) {
            call_user_func($this->__loadInSettingCallback, $this);
        }
        return $this;
    }
    public function saveInSetting($callback = null)
    {
        if ($callback) {
            $this->__saveInSettingCallback = $callback;
        } elseif ($this->__saveInSettingCallback) {
            call_user_func($this->__saveInSettingCallback, $this);
        }
        return $this;
    }
    public function keyInSetting($key)
    {
        if (!$key) {
            return $this;
        }
        return $this->loadInSetting(function () use ($key) {
            $this->setValue(Setting::get($key));
        })->saveInSetting(function () use ($key) {
            Setting::set($key, $this->getValue());
        });
    }
}
