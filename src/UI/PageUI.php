<?php

namespace Sokeio\UI;

class PageUI extends BaseUI
{
    public function initUI()
    {
        $this->className('sokeio-page-ui');
        $this->render(function () {
            if (!$this->getIcon()) {
                $this->icon('ti ti-wallpaper');
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
        $attr = $this->getAttr();
        $title = $this->getVar('title', '', true);
        $icon = $this->getIcon();
        return <<<HTML
    <div {$attr}>
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col-md col-sm-12 mb-1">
                    <h2 class="page-title">{$icon}{$title}</h2>
                </div>
                <div class="col-md-auto mb-1 col-sm-12 d-print-none">
                    <div class="d-flex align-items-center">
                    {$this->renderChilds('right')}
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            {$this->renderChilds('before')}
            {$this->renderChilds()}
            {$this->renderChilds('after')}
        </div>
    </div>
    HTML;
    }
}
