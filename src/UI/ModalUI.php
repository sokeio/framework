<?php

namespace Sokeio\UI;

class ModalUI extends BaseUI
{
    public function initUI()
    {
        $this->className('sokeio-modal-ui');
        $this->render(function () {
            if (!$this->getIcon()) {
                $this->icon('ti ti-dashboard');
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
                {$this->renderChilds()}
                {$this->renderChilds('after')}
            </div>
        </div>
    HTML;
    }
}
