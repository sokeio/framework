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
                    $params = call_user_func($params, $this);
                }
                return view($blade, $params)->render();
            }
        ]);
    }
    public function useModalButtonRight()
    {
        return $this->className('px-2 pt-2 so-modal-button-right');
    }
    public function view()
    {
        $attr = $this->getAttr();
        return <<<HTML
    <div {$attr}>{$this->getVar('text', '', true)}{$this->renderChilds()}</div>
    HTML;
    }
}
