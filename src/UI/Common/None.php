<?php

namespace Sokeio\UI\Common;

use Sokeio\UI\BaseUI;

class None extends BaseUI
{
    public function view()
    {
        return $this->renderChilds();
    }
}
