<?php

namespace Sokeio\Platform;

use Illuminate\Support\Facades\File;
use Sokeio\Platform\Concerns\WithAssetData;

class AssetManager
{
    use WithAssetData;
    public const JS = 'JS';
    public const CSS = 'CSS';
    public const SCRIPT = 'SCRIPT';
    public const STYLE = 'STYLE';
    private $assets = [];
    private $loaded = false;
    private $dataLoader = [];

    public function assetType($name, $baseType = 'theme')
    {
        $this->addJs('resources/js/app.js', $baseType, $name);
        $this->addCss('resources/sass/app.scss', $baseType, $name);
    }
    public function theme($name)
    {
        $this->assetType($name, 'theme');
    }

    public function module($name)
    {
        $this->assetType($name, 'module');
    }

    public function plugin($name)
    {
        $this->assetType($name, 'plugin');
    }
   
    public function addAssetType($location, $base, $name, $content, $type = self::JS, $priority = 10000)
    {
        $this->assets[] = [
            'location' => $location,
            'base' => $base,
            'name' => $name,
            'content' => $content,
            'type' => $type,
            'priority' => $priority ?? 0,
        ];
        $this->loaded = false;
    }
    public function addJs($content, $base = null, $name = null, $location = PLATFORM_BODY_AFTER, $priority = 10000)
    {
        $this->addAssetType($location, $base, $name, $content, self::JS, $priority);
    }
    public function addCss($content, $base = null, $name = null, $location = PLATFORM_HEAD_AFTER, $priority = 10000)
    {
        $this->addAssetType($location, $base, $name, $content, self::CSS, $priority);
    }
    public function addScript($content, $base = null, $name = null,  $location = PLATFORM_BODY_AFTER, $priority = 10000)
    {
        $this->addAssetType($location, $base, $name, $content, self::SCRIPT, $priority);
    }
    public function addStyle($content, $base = null, $name = null, $location = PLATFORM_HEAD_AFTER, $priority = 10000)
    {
        $this->addAssetType($location, $base, $name, $content, self::STYLE, $priority);
    }
    private function loadAssetFromManifest($base, $name, $location, $content, $type, $dataLoader)
    {
        $sokeio = platformBy($base);
        $sokeioItem = $sokeio->find($name);
        if (!$sokeioItem) {
            return $dataLoader;
        }
        $manifestData = $sokeioItem->getManifestData();
        if (isset($manifestData[$content]['imports'])) {
            foreach ($manifestData[$content]['imports'] as $item) {
                if (isset($manifestData[$item]['file'])) {
                    $value = $sokeioItem->url('build/' . $manifestData[$item]['file']);
                    $dataLoader[$location][$type][] = $value;
                }
            }
        }
        if (isset($manifestData[$content]['file'])) {
            $dataLoader[$location][$type][] = $sokeioItem->url('build/' . $manifestData[$content]['file']);
        } else {
            if (File::exists($sokeioItem->getPathPublic($content))) {
                $dataLoader[$location][$type][] = $sokeioItem->url($content);
            } elseif (str($content)->startsWith('http')) {
                $dataLoader[$location][$type][] = $content;
            }
        }
        return  $dataLoader;
    }
    private function loadFirst()
    {
        if ($this->loaded) {
            return;
        }
        $this->loaded = true;
        $this->assets = collect($this->assets)->sortBy('priority')->reverse()->toArray();
        $dataLoader = [];
        foreach ($this->assets as [
            'location' => $location,
            'base' => $base,
            'name' => $name,
            'content' => $content,
            'type' => $type,
            // 'priority' => $priority,
        ]) {
            if (!isset($dataLoader[$location])) {
                $dataLoader[$location] = [];
            }
            if (!isset($dataLoader[$location][$type])) {
                $dataLoader[$location][$type] = [];
            }
            if ($type == self::SCRIPT || $type == self::STYLE) {
                $dataLoader[$location][$type][] = $content;
            } elseif ($base && $name) {
                $dataLoader = $this->loadAssetFromManifest($base, $name, $location, $content, $type, $dataLoader);
            } else {
                $dataLoader[$location][$type][] = $content;
            }
        }
        $this->dataLoader = $dataLoader;
    }
    public function render($location)
    {
        $this->loadFirst();
        if (isset($this->dataLoader[$location][self::CSS])) {
            $links = $this->dataLoader[$location][self::CSS];
            for ($i = count($links) - 1; $i >= 0; $i--) {
                echo '<link rel="stylesheet" href="' . $links[$i] . '"/>';
            }
        }
        if (isset($this->dataLoader[$location][self::STYLE])) {
            echo '<style type="text/css">';
            foreach ($this->dataLoader[$location][self::STYLE] as $item) {
                echo  $item;
            }
            echo '</style>';
        }
        if (isset($this->dataLoader[$location][self::JS])) {
            echo '<script data-navigate-once  type="text/javascript">';
            echo " PlatformLoadScript(" . json_encode($this->dataLoader[$location][self::JS]) . ") ";
            echo '</script>';
        }
        if (isset($this->dataLoader[$location][self::SCRIPT])) {
            echo '<script data-navigate-once  type="text/javascript"> ';
            foreach ($this->dataLoader[$location][self::SCRIPT] as $item) {
                echo  $item;
            }
            echo  ' </script>';
        }
    }
}
