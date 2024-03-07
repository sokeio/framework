<?php

namespace Sokeio\Locales;

use Sokeio\Facades\Platform;
use Sokeio\Laravel\JsonData;
use Sokeio\Models\Language;
use Sokeio\Models\Translation;

class LocaleManager
{
    private const KEY = 'SOKEIO_LOCALE_CURRENT';
    private $hideDefaultLocale;
    private $separator = '-';
    private $defaultLocale;
    private $supportedLocales = [];
    public function __construct()
    {
        $this->hideDefaultLocale = config('sokeio.locale.hideDefaultLocale');
        $this->separator = config('sokeio.locale.separator');
        $this->defaultLocale = setting('PLATFORM_SYSTEM_LOCALE_DEFAULT', config('sokeio.locale.defaultLocale')) ?? 'us';
        $this->supportedLocales = config('sokeio.locale.supportedLocales');
    }
    public function currentLocale()
    {
        $locale = '';
        if (sokeioIsAdmin() || !Platform::CheckConnectDB()) {
            $locale = session(self::KEY);
        } else {
            $route = request()->route();
            $locale = $route->getPrefix();
        }
        if ($locale && $locale !== '') {
            return $locale;
        }
        return  $this->defaultLocale;
    }
    public function supportedLocales()
    {
        return $this->supportedLocales;
    }
    public function switchLocale($locale)
    {
        app()->setLocale($locale);
        app('session')->put(self::KEY, $locale);
    }
    public function defaultLocale()
    {
        return $this->defaultLocale;
    }
    public function getLocaleSeparator(): string
    {
        return $this->separator ?: '-';
    }
    public function isLocaleCountryBased(string $locale): bool
    {
        return strpos($locale, $this->getLocaleSeparator()) !== false;
    }

    public function getLanguageFromCountryBasedLocale(string $locale): string
    {
        return explode($this->getLocaleSeparator(), $locale)[0];
    }
    public function setLocaleApp()
    {
        try {
            app()->setLocale($this->currentLocale());
        } catch (\Exception $ex) {
            app()->setLocale($this->defaultLocale());
        }
    }
    public function has()
    {
        return true;
    }
    private function translationToTable($type, $name, $path, $lang)
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
    private const EXT_JSON = '.json';
    public function fileJsonToTable()
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

            $this->translationToTable('app', '', base_path('lang/' . $item->code . self::EXT_JSON), $item);
            foreach (['theme', 'plugin', 'module'] as $type) {
                foreach (platformBy($type)->getAll() as $data) {
                    $path = $data->getPath('resources/lang/' . $item->code . self::EXT_JSON);
                    $this->translationToTable($type, $data->getName(), $path, $item);
                }
            }
        }
    }
    private function translationToFile($item, $type)
    {
        $data = Translation::query()->where('type', $type)
            ->where('lang_id', $item->id)
            ->select(['name', 'key', 'value'])
            ->get()->groupBy('name');

        foreach ($data as $key => $arr) {
            if ($type === 'app') {
                $pathFile = base_path('lang/' . $item->code . self::EXT_JSON);
            } else {
                $pathFile = platformBy($type)->find($key)?->getPath('resources/lang/' . $item->code . self::EXT_JSON);
            }
            $jsonData =  JsonData::getJsonFromFile($pathFile) ?? [];
            foreach ($arr as $itemArr) {
                $jsonData[$itemArr->key] = $itemArr->value;
            }
            file_put_contents($pathFile, json_encode($jsonData));
        }
    }
    public function tableToFileJson()
    {
        foreach (Language::all() as $item) {
            foreach (['theme', 'plugin', 'module', 'app'] as $type) {
                $this->translationToFile($item, $type);
            }
        }
    }
}
