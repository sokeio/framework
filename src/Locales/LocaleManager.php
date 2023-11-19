<?php

namespace BytePlatform\Locales;

use BytePlatform\Facades\Module;
use BytePlatform\Facades\Platform;
use BytePlatform\Facades\Plugin;
use BytePlatform\Facades\Theme;
use BytePlatform\Laravel\JsonData;
use BytePlatform\Models\Language;
use BytePlatform\Models\Translation;

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

        return $locale || $this->defaultLocale;
    }
    public function SupportedLocales()
    {
        return $this->supportedLocales;
    }
    public function SwitchLocale($locale)
    {
        app()->setLocale($locale);
        app('session')->put(self::KEY, $locale);
    }
    public function SetLocaleApp()
    {
        app()->setLocale($this->CurrentLocale());
    }
    private function TranslationToTable($type, $name, $path, $lang)
    {
        if (file_exists($path)) {
            $data =  JsonData::getJsonFromFile($path);
            foreach ($data as $key => $value) {
                Translation::query()->updateOrCreate([
                    'type' => $type,
                    'name' => $name,
                    'key' => $key,
                    'lang_id' => $lang->id,
                ], [
                    'value' => $value
                ]);
            }
        }
    }
    public function FileJsonToTable()
    {
        $languages = JsonData::getJsonFromFile(__DIR__ . '/../../database/contents/languages.json');
        foreach ($languages as $item) {
            if (!Language::query()->where('code', $item['code'])->count()) {
                $lang = new Language();
                $lang->name = $item['name'];
                $lang->code = $item['code'];
                $lang->default = $item['default'];
                $lang->status = $item['status'] ? 1 : 0;
                $lang->save();
            }
        }
        foreach (Language::all() as $item) {
            
            $this->TranslationToTable('app', '', base_path('resources/lang/' . $item->code . '.json'), $item);
            foreach (['theme', 'plugin', 'module'] as $type) {
                foreach (platform_by($type)->getAll() as $data) {
                    $this->TranslationToTable($type, $data->getName(), $data->getPath('resources/lang/' . $item->code . '.json'), $item);
                }
            }
        }
    }
    private function TranslationToFile($item, $type)
    {
        $data = Translation::query()->where('type', $type)->where('lang_id', $item->id)->select(['name', 'key', 'value'])->get()->groupBy('name');
        foreach ($data as $key => $arr) {
            $pathFile = $type == 'app' ? base_path('resources/lang/' . $item->code . '.json') :   platform_by($type)->find($key)?->getPath('resources/lang/' . $item->code . '.json');
            $jsonData =  JsonData::getJsonFromFile($pathFile) ?? [];
            foreach ($arr as $itemArr) {
                $jsonData[$itemArr->key] = $itemArr->value;
            }
            file_put_contents($pathFile, json_encode($jsonData));
        }
    }
    public function TableToFileJson()
    {
        foreach (Language::all() as $item) {
            foreach (['theme', 'plugin', 'module', 'app'] as $type) {
                $this->TranslationToFile($item, $type);
            }
        }
    }
}
