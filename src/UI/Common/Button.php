<?php

namespace Sokeio\UI\Common;

use Sokeio\UI\BaseUI;

class Button extends BaseUI
{
    protected function initUI()
    {
        return $this->attr('type', 'button')->className('btn btn-primary');
    }
    public function text($text)
    {
        return $this->attr('type', 'button')->attr('value', $text)->vars('text', $text);
    }
    public function wireClick($callback, $actionName = 'button::click', $params = null)
    {
        if (is_string($callback) && strpos($callback, '::') === false && !class_exists($callback)) {
            return $this->attr('wire:click', $callback);
        }
        $wireClick = $this->getId() . $actionName;
        $paraText = '';
        if ($params) {
            $paraText = ',' . json_encode($params);
        }
        $this->action($wireClick, function () use ($callback, $params) {
            if (is_array($params) && count($params) > 0) {
                call_user_func($callback, ...$params);
            } else {
                call_user_func($callback);
            }
        });
        return $this->attr('wire:click',  'actionUI("' . $wireClick . '"' . $paraText . ')');
    }
    public function view()
    {
        $attr = $this->getAttr();
        return <<<HTML
        <button {$attr}>{$this->getVar('text')}</button>
        HTML;
    }
}
