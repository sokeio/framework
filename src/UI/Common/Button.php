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

    public function click($callback, $actionName = 'button::click', $params = null)
    {
        if (is_string($callback) && strpos($callback, '::') === false && !class_exists($callback)) {
            return $this->register(function () use ($callback) {
                return $this->x()->on($callback);
            });
        }
        return $this->wireClick($callback, $actionName, $params);
    }
    public function wireClick($callback, $actionName = 'button::click', $params = null)
    {
        return  $this->register(function () use ($callback, $actionName, $params) {
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
        });
    }
    protected function registerModal($modal = [])
    {
        return $this->register(function () use ($modal) {
            $title = data_get($modal, 'title');
            $url = data_get($modal, 'url');
            $size = data_get($modal, 'size');
            $icon = data_get($modal, 'icon');
            $templateId = data_get($modal, 'template-id');
            $template = data_get($modal, 'template');

            $this->attr('wire:modal', '');
            if ($title) {
                $this->attr('wire:modal.title', $title);
            }
            if ($url) {
                $this->attr('wire:modal.url', $url);
            }
            if ($size) {
                $this->attr('wire:modal.size', $size);
            }
            if ($icon) {
                $this->attr('wire:modal.icon', $icon);
            }
            if ($templateId) {
                $this->attr('wire:modal.template-id', $templateId);
            }
            if ($template) {
                $this->attr('wire:modal.template', $template);
            }
        });
    }
    public function modal(
        $url,
        $title = '',
        $size = 'lg',
        $icon = 'ti ti-dashboard'
    ) {
        return $this->registerModal(compact('url', 'title', 'size', 'icon'));
    }
    public function modalTemplate($template, $title = '', $size = 'lg', $icon = 'ti ti-dashboard')
    {
        return $this->registerModal(compact('template', 'title', 'size', 'icon'));
    }
    public function modalTemplateId($templateId, $title = '', $size = 'lg', $icon = 'ti ti-dashboard')
    {
        return $this->registerModal(compact('templateId', 'title', 'size', 'icon'));
    }
    public function modalRoute($route, $title = '', $size = 'lg', $icon = 'ti ti-dashboard')
    {
        $url = route($route);
        return $this->registerModal(compact('url', 'title', 'size', 'icon'));
    }
    public function view()
    {
        $attr = $this->getAttr();
        return <<<HTML
        <button {$attr}>{$this->getVar('text', '', true)}</button>
        HTML;
    }
}
