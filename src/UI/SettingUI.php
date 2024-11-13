<?php

namespace Sokeio\UI;

use Sokeio\UI\Field\SwitchField;

class SettingUI extends BaseUI
{
    private $keyEnable = '';
    private $valueEnable = false;
    public function title($title)
    {
        return $this->vars('title', $title);
    }
    public function showSwitcher($key = null, $default = true)
    {
        return $this->child([
            SwitchField::init('enable')
                ->labelTrue('Enable')
                ->labelFalse('Disable')->boot(function (SwitchField $item) {
                    $this->keyEnable = $item->getFieldName();
                    $this->valueEnable = $item->getValue();
                })->valueDefault($default)->keyInSetting($key)
        ], 'switcher');
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
        if ($this->getVar('subtitle', '', true)) {
            return '<p class="text-secondary">' . $this->getVar('subtitle', '', true) . '</p>';
        }
        return '';
    }
    public function view()
    {
        $htmlBodyCard = '';
        if ($this->keyEnable) {
            $htmlBodyCard = 'x-show="$wire.' . $this->keyEnable . '"';
            if (!$this->valueEnable) {
                $htmlBodyCard .= ' style="display: none;"';
            }
        }
        $html = <<<HTML
        <div {$this->getAttr()}>
            <div class="card">
                <div class="card-header p-2">
                    <h3 class="card-title">{$this->getIcon()} {$this->getVar('title', '', true)}</h3>
                    <div class="card-actions pt-2 pe-2">
                        {$this->renderChilds('switcher')}
                    </div>
                </div>
                {$this->subTitleRender()}
                <div class="card-body p-2" {$htmlBodyCard}>
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
