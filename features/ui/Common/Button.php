<?php

namespace Sokeio\UI\Common;

use Sokeio\UI\BaseUI;
use Sokeio\UI\Common\Concerns\WithCol;
use Sokeio\UI\Common\Concerns\WithTextHtml;
use Sokeio\UI\Concerns\SokeioJS;

class Button extends BaseUI
{
    use WithTextHtml, WithCol, SokeioJS;
    protected function initUI()
    {
        $this->render(function () {
            if (!$this->containsAttr('class', 'btn-')) {
                $this->className('btn btn-primary');
            }
        });
        return $this->attr('type', 'button');
    }
    public function submit($text = 'Submit')
    {
        return $this->attr('type', 'submit')->attr('value', $text)->vars('text', $text);
    }
    public function checkShowSelected($extra = '')
    {
        return $this->attr('x-show', '$wire.dataSelecteds.length>0 ' . $extra)->style('display', 'none');
    }
    public function editRowAllSelected($extra = '')
    {
        return $this->attr('x-data', "sokeioTableEditline")
            ->attr('@click', 'editRowAllSelected()')
            ->checkShowSelected($extra . ' && checkExistNotEditInSelectd');
    }
    public function CancelRowAllSelected($extra = '')
    {
        return $this->attr('x-data', "sokeioTableEditline")
            ->attr('@click', 'cancelRowAllSelected()')
            ->checkShowSelected($extra . ' && checkExistEditInSelectd');
    }

    public function SaveRowAllSelected($extra = '')
    {
        return $this->attr('x-data', "sokeioTableEditline")
            ->attr('@click', '$wire.saveData()')
            ->checkShowSelected($extra . ' && checkExistEditInSelectd');
    }



    public function wireClick(
        $callback,
        $params = null,
        $withUIIDKey = true,
        $skipRender = false,
        $actionName = 'button::click'
    ) {
        if (is_string($callback) && strpos($callback, '::') === false && !class_exists($callback)) {
            return $this->render(function ($ui) use ($callback, $params) {
                if (is_callable($params)) {
                    $params = call_user_func($params, $ui);
                }
                if (is_array($params)) {
                    $params = trim(implode(',', $params), ',');
                }
                if ($params) {
                    $callback = $callback . '(' . $params . ')';
                }
                return $this->attr('wire:click', $callback);
            });
        }
        return $this->action($actionName, function (...$params) use ($callback) {
            return  call_user_func_array($callback, $params);
        }, $withUIIDKey, $skipRender)
            ->render(function ($base) use ($actionName, $params) {
                $paraText = '';
                if (is_callable($params)) {
                    $params = call_user_func($params, $base);
                }
                if ($params) {
                    $paraText = ',' . json_encode($params);
                }
                $keyClick = $base->getUIIDkey() . $actionName;

                return $base->attr('wire:click',  'callActionUI("' . $keyClick . '"' . $paraText . ')');
            });
    }
    public function modalClose()
    {
        return $this->sokeioClick('this.closeApp()');
    }
    public function sokeioClick($callback)
    {
        return $this->attr('so-on:click', $callback);
    }
    private function getUrlFromModal($modal)
    {
        $route = data_get($modal, 'route');
        $url = data_get($modal, 'url');
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
        return $url;
    }
    protected function registerModal($modal = [])
    {
        return $this->render(function () use ($modal) {
            $url = $this->getUrlFromModal($modal);
            if ($url) {
                $this->attr('wire:modal.url', $url);
            }
            $this->attr('wire:modal', '');
            foreach (['title',  'size', 'icon', 'template-id', 'template', 'id'] as $item) {
                if ($valueItem = data_get($modal, $item)) {
                    $this->attr('wire:modal.' . $item, $valueItem);
                }
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
    public function modalSokeio($main, $components = array())
    {
        return $this->render(function () use ($main, $components) {
            $this->sokeio($main, $components, 'soapp-' . $this->getUIIDkey());
            $this->attr('wire:modal', '');
            $this->attr('wire:modal.id',  'soapp-' . $this->getUIIDkey());
        });
    }

    public function view()
    {
        $attr = $this->getAttr();
        $icon = $this->getIcon();
        $title = $this->getLabel();
        if ($title) {
            $title = '<span>' . $title . '</span>';
        }
        $view = <<<HTML
        <button {$attr}>{$icon} {$title}</button>
        {$this->renderSokeioJS()}
        HTML;
        if ($this->containsAttr('class', null, 'col')) {
            $attrCol = $this->getAttr('col');
            $view = <<<HTML
            <div {$attrCol}>
            {$view}
            </div>
            HTML;
        }
        return $view;
    }
}
