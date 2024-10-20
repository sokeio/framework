<?php

namespace Sokeio\UI\Common;

use Sokeio\UI\BaseUI;

class Button extends BaseUI
{
    protected function initUI()
    {
        $this->render(function () {
            if (!$this->containsAttr('class', 'btn-')) {
                $this->className('btn btn-primary');
            }
        });
        return $this->attr('type', 'button');
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

            $this->action($wireClick, function ($_params) use ($callback) {
                if (is_array($_params) && count($_params) > 0) {
                    call_user_func($callback, ...$_params);
                } elseif ($_params) {
                    call_user_func($callback, $_params);
                } else {
                    call_user_func($callback);
                }
            });

            return $this->render(function () use ($wireClick, $params) {
                $paraText = '';
                if (is_callable($params)) {
                    $params = call_user_func($params, $this);
                }
                if ($params) {
                    $paraText = ',' . json_encode($params);
                }
                return $this->attr('wire:click',  'callActionUI("' . $wireClick . '"' . $paraText . ')');
            });
        });
    }
    protected function registerModal($modal = [])
    {
        return $this->render(function () use ($modal) {
            $title = data_get($modal, 'title');
            $route = data_get($modal, 'route');
            $url = data_get($modal, 'url');
            $size = data_get($modal, 'size');
            $icon = data_get($modal, 'icon');
            $templateId = data_get($modal, 'template-id');
            $template = data_get($modal, 'template');
            if ($route) {
                if (is_callable($route)) {
                    $route = call_user_func($route, $this);
                }
                if (is_array($route) && count($route) > 1) {
                    $url = route($route[0], $route[1]);
                } else {
                    $url = route($route);
                }
            }

            if ($url && is_callable($url)) {
                $url = call_user_func($url, $this);
            }
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
        return $this->registerModal(compact('route', 'title', 'size', 'icon'));
    }
    public function view()
    {
        $attr = $this->getAttr();
        return <<<HTML
        <button {$attr}>{$this->getVar('text', '', true)}</button>
        HTML;
    }
}
