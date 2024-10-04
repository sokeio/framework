<?php

namespace Sokeio\UI\Field;

use Sokeio\UI\BaseUI;

class FieldUI extends BaseUI
{
    public function view()
    {
        $attr = $this->getAttr();
        return <<<HTML
        <div {$attr}>Demo 123</div>
        HTML;
    }
}
