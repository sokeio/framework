<?php

namespace Sokeio\UI\Common;

use Sokeio\UI\BaseUI;
use Sokeio\UI\Common\Concerns\DivAlert;
use Sokeio\UI\Common\Concerns\DivGrid;
use Sokeio\UI\Common\Concerns\WithTextHtml;
use Sokeio\UI\Common\Concerns\WithViewBlade;

class Div extends BaseUI
{
    use WithTextHtml;
    use DivGrid, DivAlert, WithViewBlade;
    public function text($text)
    {
        return $this->vars('text', $text);
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
