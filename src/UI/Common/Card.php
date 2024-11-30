<?php

namespace Sokeio\UI\Common;

use Sokeio\UI\BaseUI;
use Sokeio\UI\Common\Concerns\WithViewBlade;

class Card extends BaseUI
{
    use WithViewBlade;
    protected function initUI()
    {
        $this->render(function () {
            if (!$this->containsAttr('class', 'card')) {
                $this->className('card');
            }
        });
    }

    public function title($title)
    {
        return $this->vars('title', $title);
    }
    public function bodyClass($bodyClass)
    {
        return $this->vars('bodyClass', $bodyClass);
    }

    public function subtitle($subtitle)
    {
        return $this->vars('subtitle', $subtitle);
    }
    public function column($column)
    {
        return $this->vars('column', $column);
    }
    private function subtitleRender()
    {
        if ($this->checkVar('subtitle')) {
            return <<<HTML
            <p class="text-secondary">{$this->getVar('subtitle', '', true)}</p>
            HTML;
        }
        return '';
    }
    private function titleRender()
    {
        if ($this->checkVar('title')) {
            return <<<HTML
            <h3 class="card-title">{$this->getVar('title', '', true)}</h3>
            HTML;
        }
        return '';
    }
    public function view()
    {
        $attr = $this->getAttr();
        $html = <<<HTML
            <div {$attr}>
                <div class="card-body {$this->getVar('bodyClass', 'p-2', true)}">
                    {$this->titleRender()}
                    {$this->subtitleRender()}
                    {$this->getVar('text', '', true)}{$this->renderChilds()}
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
