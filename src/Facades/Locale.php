<?php

namespace Sokeio\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void setLocaleApp()
 * @method static string currentLocale()
 * @method static string defaultLocale()
 * @method static string getLanguageFromCountryBasedLocale(string $locale)
 * @method static array supportedLocales()
 * @method static void switchLocale(string $locale)
 * @method static void fileJsonToTable();
 * @method static void tableToFileJson();
 * @method static bool has($key)
 * @method static bool isLocaleCountryBased(string $locale)
 * @see \Sokeio\Facades\Locale
 */
class Locale extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Sokeio\Locales\LocaleManager::class;
    }
}
