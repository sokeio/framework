<?php

namespace Sokeio\Locales\Extractor;

use Sokeio\Facades\Module;
use Sokeio\Facades\Plugin;
use Sokeio\Facades\Theme;
use Sokeio\Laravel\JsonData;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\Finder;

class TranslationFinder
{
    /**
     * Directories to search in.
     *
     * @var array
     */
    protected array $directories;

    /**
     * Directories to exclude from search.
     *
     * @var array
     */
    protected array $excludedDirectories;

    /**
     * File patterns to search for.
     *
     * @var array
     */
    protected array $patterns;

    /**
     * Finder constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->directories = config(
            'sokeio.locale.exporter.directories',
            [
                'app',
                'resources',
                'src'
            ]
        );
        $this->excludedDirectories = config(
            'sokeio.locale.exporter.excluded-directories',
            [
                'node_modules'
            ]
        );
        $this->patterns = config(
            'sokeio.locale.exporter.patterns',
            [
                '*.php',
                '*.js',
            ]
        );
    }
    private function findByPath($path)
    {
        $directories = $this->directories;
        array_walk($directories, function (&$item) use ($path) {
            $item = $path . DIRECTORY_SEPARATOR . $item;
        });
        $directories = collect($directories)->where(function ($ite) {
            return File::exists($ite);
        })->toArray();
        $excludedDirectories = $this->excludedDirectories;

        $finder = new Finder();

        $finder = $finder->in($directories);
        $finder = $finder->exclude($excludedDirectories);

        foreach ($this->patterns as $pattern) {
            $finder->name($pattern);
        }

        return $finder->files();
    }
    /**
     * Find all files that can contain translatable strings.
     *
     * @return \Symfony\Component\Finder\Finder|null
     */
    public function find()
    {
        $arr = [];
        $arr[] = PlatformCodeParser::extractTranslation('app', $this->findByPath(base_path()));
        foreach (Module::getAll() as $module) {
            $path = $this->findByPath($module->getPath());
            $arr[] = PlatformCodeParser::extractTranslation('module', $path, $module->getName());
        }
        foreach (Plugin::getAll() as $plugin) {
            $path = $this->findByPath($plugin->getPath());
            $arr[] = PlatformCodeParser::extractTranslation('plugin', $path, $plugin->getName());
        }
        foreach (Theme::getAll() as $theme) {
            $path = $this->findByPath($theme->getPath());
            $arr[] = PlatformCodeParser::extractTranslation('theme', $path, $theme->getName());
        }
        return $arr;
    }
    public static function toJson()
    {
        return (new self())->find();
    }
    public static function updateToJson(): void
    {
        try {
            foreach (self::toJson() as $item) {
                if ($item['type'] === 'app') {
                    continue;
                }

                $itemBase = platformBy($item['type'])->find($item['name']);
                $path_local = $itemBase->getPath('resources/lang/en.json');
                $arr = File::exists($path_local) ? JsonData::getJsonFromFile($path_local) : [];
                $translation = $item['translation'];
                foreach ($translation as $key => $value) {
                    if (!isset($arr[$key])) {
                        $arr[$key] = $value;
                    }
                }

                if (!File::exists($itemBase->getPath('resources/lang'))) {
                    File::makeDirectory($itemBase->getPath('resources/lang'), 0775, true);
                }
                $text = json_encode($arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT);
                file_put_contents($path_local, $text);
            }
        } catch (\Exception $e) {
            //do nothing
        }
    }
}
