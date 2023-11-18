<?php

namespace BytePlatform\Locales\Extractor;

use BytePlatform\Facades\Module;
use BytePlatform\Facades\Plugin;
use BytePlatform\Facades\Theme;
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
            'byte.locale.exporter.directories',
            [
                'app',
                'resources',
                'src'
            ]
        );
        $this->excludedDirectories = config(
            'byte.locale.exporter.excluded-directories',
            [
                'node_modules'
            ]
        );
        $this->patterns = config(
            'byte.locale.exporter.patterns',
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
            $arr[] = PlatformCodeParser::extractTranslation('module', $this->findByPath($module->getPath()), $module->getName());
        }
        foreach (Plugin::getAll() as $plugin) {
            $arr[] = PlatformCodeParser::extractTranslation('plugin', $this->findByPath($plugin->getPath()), $plugin->getName());
        }
        foreach (Theme::getAll() as $theme) {
            $arr[] = PlatformCodeParser::extractTranslation('theme', $this->findByPath($theme->getPath()), $theme->getName());
        }
        return $arr;
    }
    public static function toJson()
    {
        return (new self())->find();
    }
}
