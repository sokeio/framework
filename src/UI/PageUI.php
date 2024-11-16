<?php

namespace Sokeio\UI;

use Illuminate\Support\Facades\Request;

class PageUI extends BaseUI
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
    private function isModal()
    {
        return $this->getWire()->isPageAjax || $this->useModal;
    }
    public function initUI()
    {
        $this->render(function () {
            if ($this->isModal()) {
                $this->className('sokeio-modal-ui');
                if (!$this->getIcon()) {
                    $this->icon('ti ti-dashboard');
                }
                if (!$this->overlayClose) {
                    $this->attr('data-skip-overlay-close', true);
                }
            } else {
                if ($this->onlyModal) {
                    abort(404);
                }
                $this->className('sokeio-page-ui');
                if (!$this->getIcon()) {
                    $this->icon('ti ti-wallpaper');
                }
            }
        });
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
    public function row()
    {
        return $this->vars('row', 'row');
    }
    public function card($class = '')
    {
        return $this->vars('card', 'card ' . $class);
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
    private function getPageView()
    {
        $attr = $this->getAttr();
        $title = $this->getVar('title', '', true);
        $icon = $this->getIcon();
        $card = $this->getVar('card', '', true);
        return <<<HTML
            <div {$attr}>
                <div class="page-header d-print-none">
                    <div class="row align-items-center">
                        <div class="col-md col-sm-12 mb-1">
                            <h2 class="page-title">{$icon}{$title}</h2>
                        </div>
                        <div class="col-md-auto mb-1 col-sm-12 d-print-none page-header-actions">
                            <div class="d-flex ">
                            {$this->renderChilds('right')}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="page-body {$card}">
                    {$this->renderChilds('before')}
                    {$this->contentChildrenRender()}
                    {$this->renderChilds('after')}
                </div>
            </div>
            HTML;
    }
    private function getModalView()
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
    public function view()
    {
        if ($this->isModal()) {
            return $this->getModalView();
        }
        return $this->getPageView();
    }
}
