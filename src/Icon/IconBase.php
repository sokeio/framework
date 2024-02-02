<?php

namespace Sokeio\Icon;


class IconBase
{
    public function AddToAsset()
    {
    }
    public static function Make()
    {
        return new static();
    }
    public function getKey()
    {
        return 'icon base';
    }
    public function getName()
    {
        return 'icon base';
    }
    public function getPre()
    {
        return '';
    }
    public function getRegax()
    {
        return '/\.' . $this->getPre() . '-(.*?):before/';
    }
    public function getPathIcon()
    {
        return '';
    }
    public function getFileContentIcon()
    {
        if (!file_exists($this->getPathIcon()))
            return '';
        return file_get_contents($this->getPathIcon());
    }
    public function getIconSize()
    {
        return [];
    }
    public function getItems()
    {
        $fileContent = $this->getFileContentIcon();
        if ($fileContent == '')
            return [];
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
