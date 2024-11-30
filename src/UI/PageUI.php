<?php

namespace Sokeio\UI;

use Sokeio\UI\Concerns\WithModal;

class PageUI extends BaseUI
{
    use WithModal;

    private function isModal()
    {
        return $this->getWire()->isPageAjax || $this->useModal;
    }
    public function initUI()
    {
        $this->boot(function () {
            if ($this->onlyModal && !$this->isModal()) {
                abort(404);
            }
        });
        $this->render(function () {
            if (!$this->checkVar('title')) {
                $this->title($this->getWire()->getPageTitle());
            }
            if (!$this->checkVar('icon')) {
                $this->icon($this->getWire()->getPageIcon());
            }
            if (!$this->checkVar('icon')) {
                $this->icon('ti ti-dashboard');
            }
            if ($this->isModal()) {
                $this->className('sokeio-modal-ui');
                if (!$this->overlayClose) {
                    $this->attr('data-skip-overlay-close', true);
                }
            } else {
                $this->className('sokeio-page-ui');
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
    public function hidePageHeader()
    {
        return $this->vars('hidePageHeader', true);
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
    private function getPageHeadView()
    {
        if ($this->checkVar('hidePageHeader')) {
            return '';
        }
        $title = $this->getVar('title', '', true);
        $icon = $this->getIcon();
        return <<<HTML
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
            HTML;
    }
    private function getPageView()
    {
        $attr = $this->getAttr();

        $card = $this->getVar('card', '', true);
        return <<<HTML
            <div {$attr}>
                {$this->getPageHeadView()}
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
        $this->className('position-relative p-2');
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
