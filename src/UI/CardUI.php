<?php

namespace Sokeio\UI;

use Sokeio\UI\Field\SwitchField;

class CardUI extends BaseUI
{
    private $hideBody = false;
    public function title($title)
    {
        return $this->vars('title', $title);
    }
    public function hideBody($hideBody = true)
    {
        $this->hideBody = $hideBody;
        return $this;
    }
    public function hideSwitcher()
    {
        return $this->vars('hideSwitcher', true);
    }
    public function subtitle($subtitle)
    {
        return $this->vars('subtitle', $subtitle);
    }
    public function column($column)
    {
        return $this->vars('column', $column);
    }
    private function subTitleRender()
    {
        if ($subtitle = $this->getVar('subtitle', '', true)) {
            return '<p class="text-secondary">' . $subtitle . '</p>';
        }
        return '';
    }
    public function bodyRow($class = '')
    {
        return $this->vars('bodyRow', 'row ' . $class);
    }
    public function view()
    {
        $htmlBodyCard = '';

        $classBody = $this->getVar('bodyRow', '', true);
        if (!$classBody) {
            $classBody = 'p-2';
        }
        $showBody = "true";
        if ($this->hideBody) {
            $showBody = "false";
        }
        $hideSwitcher="";
        if($this->checkVar('hideSwitcher')) {
            $hideSwitcher = "style='display: none;'";
        }
        $html = <<<HTML
        <div {$this->getAttr()}>
            <div class="card" x-data="{showBody: {$showBody}}">
                <div class="card-header p-2">
                    <h3 class="card-title">{$this->getIcon()} {$this->getVar('title', '', true)}</h3>
                    <div class="card-actions pt-2 pe-2">
                        <label {$hideSwitcher} class="form-check form-switch">
                            <input x-model="showBody" class="form-check-input" type="checkbox" >
                        </label>
                    </div>
                </div>
                <div x-show="showBody" class="card-body {$classBody}" {$htmlBodyCard}>
                    {$this->subTitleRender()}
                    {$this->renderChilds()}
                </div>
            </div>
        </div>
        HTML;
        if ($this->getVar('column', '', true)) {
            $html = <<<HTML
            <div class="{$this->getVar('column', '', true)}">
                {$html}
            </div>
            HTML;
        }
        return $html;
    }
}
