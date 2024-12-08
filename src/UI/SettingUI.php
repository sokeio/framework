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
            SwitchField::make($key)
                ->labelTrue('Enable')
                ->labelFalse('Disable')
                ->valueDefault($default)
                ->boot(function (SwitchField $item) use ($default) {
                    $this->keyEnable = $item->getFieldName();
                    $this->valueEnable = $item->getValue() === null ? $default : $item->getValue();
                    $this->setParams(['setting_enable' => $this->valueEnable]);
                })
                ->boot(function () {
                    $this->setupChild(function ($item) {
                        if (method_exists($item, 'whenRule') === false) {
                            return;
                        }
                        $item->whenRule(function ($item) {
                            return $item->getParams('setting_enable') === true;
                        });
                    });
                })
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
    public function bodyRow($class = '')
    {
        return $this->vars('bodyRow', 'row ' . $class);
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
        $classBody = $this->getVar('bodyRow', '', true);
        if (!$classBody) {
            $classBody = 'p-2';
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
                <div class="card-body {$classBody}" {$htmlBodyCard}>
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
