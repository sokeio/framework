<?php

namespace Sokeio\UI\Common;

use Sokeio\UI\BaseUI;
use Sokeio\UI\Common\Concerns\WithTextHtml;

class Tag extends BaseUI
{
    use WithTextHtml;
    protected function initUI()
    {
        $this->render(function () {});
    }
    public function i()
    {
        return $this->name('i');
    }
    public function span()
    {
        return $this->name('span');
    }
    public function p()
    {
        return $this->name('p');
    }
    public function a($href)
    {
        return $this->name('a')->attr('href', $href)->attr('target', '_blank');
    }

    public function name($name)
    {
        return $this->vars('name', $name);
    }
    public function view()
    {
        $attr = $this->getAttr();
        $name = trim($this->getVar('name', '', true));
        return <<<HTML
        <$name {$attr}>{$this->renderChilds()}</$name>
        HTML;
    }
}
