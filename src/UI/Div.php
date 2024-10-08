<?php

namespace Sokeio\UI;

class Div extends BaseUI
{
    public function container($size = '')
    {
        return $this->className('container' . ($size ? '-' . $size : ''));
    }
    //success,warning,danger,info
    public function alert($type = 'success')
    {
        return $this->className('alert alert-' . $type)->attr('role', 'alert');
    }
    public function view()
    {
        $attr = $this->getAttr();
        return <<<HTML
    <div {$attr}>{$this->renderChilds()}</div>
    HTML;
    }
}
