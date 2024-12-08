<?php

namespace Sokeio\UI\Concerns;

trait ModalUI
{
    private $overlayClose = false;
    private $useModal = false;
    private $onlyModal = false;
    public function useModal()
    {
        $this->useModal = true;
        return $this;
    }
    public function onlyModal()
    {
        $this->onlyModal = true;
        return $this;
    }
    public function overlayClose()
    {
        $this->overlayClose = true;
        return $this;
    }
    public function size($size)
    {
        return $this->attr('data-modal-size', $size);
    }
    public function autoSize()
    {
        return $this->size('auto');
    }
    public function lgSize()
    {
        return $this->size('lg');
    }
    public function smSize()
    {
        return $this->size('sm');
    }
    public function mdSize()
    {
        return $this->size('md');
    }
    public function xlSize()
    {
        return $this->size('xl');
    }
    public function xxlSize()
    {
        return $this->size('xxl');
    }
    public function fullscreenSize()
    {
        return $this->size('fullscreen');
    }

    public function fullscreenXlSize()
    {
        return $this->size('fullscreen-xl-down');
    }

    public function fullscreenLgSize()
    {
        return $this->size('fullscreen-lg-down');
    }

    public function fullscreenMdSize()
    {
        return $this->size('fullscreen-md-down');
    }

    public function fullscreenSmSize()
    {
        return $this->size('fullscreen-sm-down');
    }


    public function hideButtonClose()
    {
        return $this->attr('data-hide-button-close', true);
    }
}
