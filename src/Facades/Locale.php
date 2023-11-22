<?php

namespace Sokeio\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * 
 * @method static void SetLocaleApp()
 * @method static string CurrentLocale()
 * @method static array SupportedLocales()
 * @method static void SwitchLocale(string $locale)
 * @method static void FileJsonToTable();
 * @method static void TableToFileJson();
 *
 * @see \Sokeio\Facades\Locale
 */
class Locale extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Sokeio\Locales\LocaleManager::class;
    }
}
