<?php

namespace Sokeio\UI\Common;

use Sokeio\UI\BaseUI;

class Div extends BaseUI
{
    public function text($text)
    {
        return $this->vars('text', $text);
    }
    public function container($size = '')
    {
        return $this->className('container' . ($size ? '-' . $size : ''));
    }
    //success,warning,danger,info
    public function alert($type = 'success')
    {
        return $this->className('alert alert-' . $type)->attr('role', 'alert');
    }
    public function viewBlade($blade)
    {
        return $this->child([
            function () use ($blade) {
                return view($blade)->render();
            }
        ]);
    }
    public function view()
    {
        $attr = $this->getAttr();
        return <<<HTML
    <div {$attr}>{$this->getVar('text', '', true)}{$this->renderChilds()}</div>
    HTML;
    }
}
