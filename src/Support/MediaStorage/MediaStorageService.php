<?php

namespace Sokeio\Support\MediaStorage;

class MediaStorageService
{
    public function getName()
    {
        return '';
    }
    public function getIcon()
    {
        return '';
    }
    public function check()
    {
        return true;
    }
    public function getViews()
    {
        return [];
    }
    public function getActions() {}
    public function getToolbar() {}
    public function getMenu() {}
    public function getFiles($action, $path, $data) {}
    public function getFolders($action, $path, $data) {}

    public function getData($action, $path, $data)
    {
        return [
            'files' => $this->getFiles($action, $path, $data),
            'folders' => $this->getFolders($action, $path, $data),
            'toolbar' => $this->getToolbar(),
            'menu' => $this->getMenu(),
            'views' => $this->getViews(),
            'actions' => $this->getActions(),
        ];
    }

    public function action($action, $path, $data)
    {
        if (method_exists($this, $action . 'Action')) {
            $this->{$action . 'Action'}($path, $data);
        }
        return $this->getData($action, $path, $data);
    }
}
