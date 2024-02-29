<?php

namespace Sokeio\Platform;

use Illuminate\Support\Facades\File;

class AssetManager
{
    public const JS = 'JS';
    public const CSS = 'CSS';
    public const SCRIPT = 'SCRIPT';
    public const STYLE = 'STYLE';
    private $data = [];
    private $assets = [];
    private $loaded = false;
    private $dataLoader = [];

    public function setFavicon($favicon)
    {
        $this->data['favicon'] = $favicon;
    }
    public function setTitle($title)
    {
        $this->data['title'] = $title;
    }
    public function setDescription($description)
    {
        $this->data['description'] = $description;
    }
    public function getFavicon()
    {
        return $this->data['favicon'] ?? '';
    }

    public function getTitle()
    {
        return $this->data['title'] ?? '';
    }
    public function getDescription()
    {
        return $this->data['description'] ?? '';
    }

    public function AssetType($name, $baseType = 'theme')
    {
        $this->AddJs('resources/js/app.js', $baseType, $name);
        $this->AddCss('resources/sass/app.scss', $baseType, $name);
    }
    public function Theme($name)
    {
        $this->AssetType($name, 'theme');
    }

    public function Module($name)
    {
        $this->AssetType($name, 'module');
    }

    public function Plugin($name)
    {
        $this->AssetType($name, 'plugin');
    }
    public function SetData($key, $value)
    {
        $this->data[$key] = $value;
    }
    public function GetData($key, $default = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }
    public function AddAssetType($location, $base, $name, $content, $type = self::JS, $priority = 10000)
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
    public function AddJs($content, $base = null, $name = null, $location = PLATFORM_BODY_AFTER, $priority = 10000)
    {
        $this->AddAssetType($location, $base, $name, $content, self::JS, $priority);
    }
    public function AddCss($content, $base = null, $name = null, $location = PLATFORM_HEAD_AFTER, $priority = 10000)
    {
        $this->AddAssetType($location, $base, $name, $content, self::CSS, $priority);
    }
    public function AddScript($content, $base = null, $name = null,  $location = PLATFORM_BODY_AFTER, $priority = 10000)
    {
        $this->AddAssetType($location, $base, $name, $content, self::SCRIPT, $priority);
    }
    public function AddStyle($content, $base = null, $name = null, $location = PLATFORM_HEAD_AFTER, $priority = 10000)
    {
        $this->AddAssetType($location, $base, $name, $content, self::STYLE, $priority);
    }
    private function loadFirst()
    {
        if ($this->loaded) return;
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
            if (!isset($dataLoader[$location])) $dataLoader[$location] = [];
            if (!isset($dataLoader[$location][$type])) $dataLoader[$location][$type] = [];
            if ($type == self::SCRIPT || $type == self::STYLE)
                $dataLoader[$location][$type][] = $content;
            else if ($base && $name) {
                $sokeio = platform_by($base);
                $sokeioItem = $sokeio->find($name);
                if ($sokeioItem) {
                    $manifestData = $sokeioItem->getManifestData();
                    if (isset($manifestData[$content]['imports'])) {
                        foreach ($manifestData[$content]['imports'] as $item) {
                            if (isset($manifestData[$item]['file'])) {
                                $dataLoader[$location][$type][] = $sokeioItem->url('build/' . $manifestData[$item]['file']);
                            }
                        }
                    }
                    if (isset($manifestData[$content]['file'])) {
                        $dataLoader[$location][$type][] = $sokeioItem->url('build/' . $manifestData[$content]['file']);
                    } else {
                        if (File::exists($sokeioItem->getPathPublic($content))) {
                            $dataLoader[$location][$type][] = $sokeioItem->url($content);
                        } else if (str($content)->startsWith('http')) {
                            $dataLoader[$location][$type][] = $content;
                        }
                    }
                }
            } else {
                $dataLoader[$location][$type][] = $content;
            }
        }
        $this->dataLoader = $dataLoader;
    }
    public function Render($location)
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
