<?php

namespace Sokeio\UI;

class ModalUI extends BaseUI
{
    private $overlayClose = false;
    public function initUI()
    {
        $this->className('sokeio-modal-ui');
        $this->render(function () {
            if (!$this->getIcon()) {
                $this->icon('ti ti-dashboard');
            }
            if (!$this->overlayClose) {
                $this->attr('data-skip-overlay-close', true);
            }
        });
    }
    public function overlayClose()
    {
        $this->overlayClose = true;
        return $this;
    }
    public function title($title)
    {
        return $this->vars('title', $title);
    }
    public function rightUI($ui)
    {
        return $this->child($ui, 'right');
    }
    public function beforeUI($ui)
    {
        return $this->child($ui, 'before');
    }
    public function afterUI($ui)
    {
        return $this->child($ui, 'after');
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
    public function  row()
    {
        return $this->vars('row', 'row');
    }
    private function contentChildrenRender()
    {
        if ($this->checkVar('row')) {
            return <<<HTML
            <div class="row">
                {$this->renderChilds()}
            </div>
            HTML;
        }
        return $this->renderChilds();
    }

    public function view()
    {
        $this->className('position-relative');
        $attr = $this->getAttr();
        $title = $this->getVar('title', '', true);
        $icon = $this->getIcon();
        return <<<HTML
        <div {$attr}>
            <div wire:loading class="position-absolute top-50 start-50 translate-middle">
                <span  class="spinner-border  text-blue  " role="status"></span>
            </div>
            <div class="row align-items-center sokeio-modal-header">
                <div class="col">
                    {$icon}
                    <span>{$title}</span>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="d-flex align-items-center">
                    {$this->renderChilds('right')}
                    </div>
                </div>
            </div>
            <div class="sokeio-modal-body p-1">
                {$this->renderChilds('before')}
                {$this->contentChildrenRender()}
            </div>
            <div class="sokeio-modal-footer d-print-none">
            {$this->renderChilds('after')}
            </div>
        </div>
    HTML;
    }
}
