<?php

namespace BytePlatform\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * 
 * @method static void SetLocaleApp()
 * @method static string CurrentLocale()
 * @method static array SupportedLocales()
 * @method static void SwitchLocale(string $locale)
 * 
 *
 * @see \BytePlatform\Facades\Locale
 */
class Locale extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \BytePlatform\Locales\LocaleManager::class;
    }
}
