<?php

namespace Sokeio\UI\Common;

use Sokeio\UI\BaseUI;

class Card extends BaseUI
{
    protected function initUI()
    {
        $this->render(function () {
            if (!$this->containsAttr('class', 'card')) {
                $this->className('card');
            }
        });
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
    public function title($title)
    {
        return $this->vars('title', $title);
    }
    public function subtitle($subtitle)
    {
        return $this->vars('subtitle', $subtitle);
    }
    public function column($column)
    {
        return $this->vars('column', $column);
    }
    public function view()
    {
        $attr = $this->getAttr();
        $html = <<<HTML
        <div {$attr}>
        <div class="card-body">
        <h3 class="card-title">{$this->getVar('title', '', true)}</h3>
        <p class="text-secondary">{$this->getVar('subtitle', '', true)}</p>
        </div>
        {$this->getVar('text', '', true)}{$this->renderChilds()}
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
