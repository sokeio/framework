<?php

namespace Sokeio\UI;

class PageUI extends BaseUI
{
    public function initUI()
    {
        $this->className('sokeio-page-ui');
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
        return <<<HTML
    <div {$attr}>
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">{$title}</h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
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
