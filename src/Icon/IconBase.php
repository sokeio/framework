<?php

namespace Sokeio\Icon;


abstract class IconBase
{
    abstract public function addToAsset();
    public static function make()
    {
        return new static();
    }
    public function getKey(): string
    {
        return 'icon key';
    }
    public function getName(): string
    {
        return 'icon name';
    }
    public function getPre(): string
    {
        return '';
    }
    public function getRegax(): string
    {
        return '/\.' . $this->getPre() . '-(.*?):before/';
    }
    public function getPathIcon(): string
    {
        return 'path icon';
    }
    public function getFileContentIcon()
    {
        if (!file_exists($this->getPathIcon())) {
            return '';
        }
        return file_get_contents($this->getPathIcon());
    }
    public function getIconSize()
    {
        return [];
    }
    public function getItems()
    {
        $fileContent = $this->getFileContentIcon();
        if ($fileContent == '') {
            return [];
        }
        $matches = [];
        preg_match_all($this->getRegax(), $fileContent, $matches);
        $rs = [];
        foreach ($matches[1] as $name) {
            $rs[] = [
                'base' => $this->getName(),
                'name' => str_replace('-', ' ',  trim($name, ':')),
                'value' => trim($name, ':'),
                'icon' => $this->getPre() . ' ' . $this->getPre() . '-' . trim($name, ':'),
                'pre' => $this->getPre(),
                'regax' => $this->getRegax(),
                'icon-size' => $this->getIconSize(),
            ];
        }
        return $rs;
    }
    public function toArray($withItems = true)
    {
        if ($withItems) {
            return [
                'key' => $this->getKey(),
                'base' => $this->getName(),
                'pre' => $this->getPre(),
                'regax' => $this->getRegax(),
                'items' => $this->getItems(),
                'icon-size' => $this->getIconSize(),
            ];
        }
        return [
            'key' => $this->getKey(),
            'base' => $this->getName(),
            'pre' => $this->getPre(),
            'regax' => $this->getRegax(),
            'icon-size' => $this->getIconSize(),
        ];
    }
}
