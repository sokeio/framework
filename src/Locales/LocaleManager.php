<?php

namespace BytePlatform\Locales;

use BytePlatform\Facades\Platform;

class LocaleManager
{
    private const KEY = 'BYTE_LOCALE_CURRENT';
    private $hideDefaultLocale;
    private $defaultLocale;
    private $supportedLocales = [];
    public function __construct()
    {
        $this->hideDefaultLocale = config('byte.locale.hideDefaultLocale');
        $this->defaultLocale = config('byte.locale.defaultLocale');
        $this->supportedLocales = config('byte.locale.supportedLocales');
    }
    public function CurrentLocale()
    {
        $locale = $this->defaultLocale;
        if (byte_is_admin() || !Platform::CheckConnectDB()) {
            $locale = session(self::KEY);
        } else {
            $route = request()->route();
            $locale = $route->getPrefix();
        }
        if (in_array($locale, $this->SupportedLocales())) {
            return $locale;
        }
        return $this->defaultLocale;
    }
    public function SupportedLocales()
    {
        return $this->supportedLocales;
    }
    public function SwitchLocale($locale)
    {
        if (in_array($locale, $this->SupportedLocales())) {
            app()->setLocale( $locale);
            app('session')->put(self::KEY, $locale);
        }
    }
    public function SetLocaleApp()
    {
        app()->setLocale($this->CurrentLocale());
    }
}
