<?php

namespace Sokeio\UI\Field\Concerns;

use Sokeio\Setting;

trait WithFieldInSetting
{
    private $loadInSettingCallback;
    private $saveInSettingCallback;

    public function loadInSetting($callback = null)
    {
        if ($callback) {
            $this->loadInSettingCallback = $callback;
        } elseif ($this->loadInSettingCallback) {
            call_user_func($this->loadInSettingCallback, $this);
        } else {
            $this->setValue(Setting::get($this->getFieldNameWithoutPrefix()));
        }
        return $this;
    }
    public function saveInSetting($callback = null)
    {
        if ($callback) {
            $this->saveInSettingCallback = $callback;
        } elseif ($this->saveInSettingCallback) {
            call_user_func($this->saveInSettingCallback, $this);
        } else {
            Setting::set($this->getFieldNameWithoutPrefix(), $this->getValue());
        }
        return $this;
    }
    public function keyInSetting($key = null)
    {
        if (!$key) {
            $key = $this->getFieldNameWithoutPrefix();
        }
        return $this->loadInSetting(function () use ($key) {
            $this->setValue(Setting::get($key));
        })->saveInSetting(function () use ($key) {
            Setting::set($key, $this->getValue());
        });
    }
}
