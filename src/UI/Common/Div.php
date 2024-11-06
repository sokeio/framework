<?php

namespace Sokeio\UI\Common;

use Sokeio\UI\BaseUI;
use Sokeio\UI\Common\Concerns\DivAlert;
use Sokeio\UI\Common\Concerns\DivGrid;

class Div extends BaseUI
{
    use DivGrid, DivAlert;
    public function text($text)
    {
        return $this->vars('text', $text);
    }

    public function viewBlade($blade, $params = [])
    {
        return $this->child([
            function () use ($blade, $params) {
                if (is_callable($params)) {
                    $params = $params();
                }
                return view($blade, $params)->render();
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
