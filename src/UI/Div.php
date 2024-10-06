<?php

namespace Sokeio\UI;

class Div extends BaseUI
{
    public function warp($child)
    {
        return $this->child($child);
    }
    public function container($size = '')
    {
        return $this->className('container' . ($size ? '-' . $size : ''));
    }
    public function view()
    {
        $attr = $this->getAttr();
        return <<<HTML
    <div {$attr}>{$this->renderChilds()}</div>
    HTML;
    }
}
