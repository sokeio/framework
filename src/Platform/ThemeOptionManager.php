<?php

namespace Sokeio\Platform;

use Sokeio\Facades\Theme;

class ThemeOptionManager
{
  private $option = [];
  public function __construct()
  {
    $this->reload();
  }
  public function reload()
  {
    if ($site = Theme::SiteDataInfo()) {
      $this->option = setting('theme_option_' . $site->id, []);
    }
  }
  public function saveOption()
  {
    if ($site = Theme::SiteDataInfo()) {
      set_setting('theme_option_' . $site->id, $this->option ?? []);
    }
  }
  private $optionUI = [];
  public function optionUI($option)
  {
    $this->optionUI = $option;
    return $this;
  }
  public function getOptionUI()
  {
    return $this->optionUI;
  }
  public function checkOptionUI()
  {
    return isset($this->optionUI) && $this->optionUI && is_array($this->optionUI) && count($this->optionUI);
  }
  public function getValue($key, $default = null)
  {
    if (isset($this->option[$key])) {
      return $this->option[$key];
    }
    return $default;
  }

  public function setValue($key, $value, $saveNow = true)
  {
    if (!is_array($this->option)) $this->option = [];
    $this->option[$key] = $value;
    if ($saveNow) {
      $this->saveOption();
    }
    return $this;
  }
}
