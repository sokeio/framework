<?php

namespace Sokeio\UI\Concerns;

use Illuminate\Support\Collection;
use Sokeio\UI\BaseUI;
use Sokeio\UI\Common\None;
use Sokeio\UI\SoUI;

trait CoreChildUI
{
    private $childs = [];
    private $group = 'default';
    public function getGroup()
    {
        return $this->group;
    }
    public function group($group)
    {
        $this->group = $group;
        $this->setupChild(fn($c) => $c->group($group));
        return $this;
    }
    public function getChilds()
    {
        return $this->childs;
    }
    public function registerIDChild()
    {
        foreach ($this->childs as $group => $uis) {
            $this->callbackUI(
                $uis,
                function ($c, $key) use ($group) {
                    $c->setParent($this)
                        ->group($group)
                        ->uiId(
                            str($group . '-' . $key)
                                ->replace('.', '-')
                                ->replace('-', '_')
                        );
                }

            );
        }
        return $this;
    }
    public function setupChild($callback = null)
    {
        if (!$callback || !is_callable($callback) || empty($this->childs)) {
            return $this;
        }
        $this->callbackUI($this->childs, fn($c) => $callback($c));

        return $this;
    }
    protected function callbackUI($uis, $callback = null)
    {
        foreach ($uis as $key => $child) {
            if ($child instanceof BaseUI) {
                $callback($child, $key);
            }
            if (is_array($child)) {
                $this->callbackUI($child, $callback);
            }
        }
        return $this;
    }
    public function child($childs = [], $group = 'default', $prepend = false)
    {
        if (!$childs) {
            return $this;
        }
        if ($childs instanceof Collection) {
            $childs = $childs->toArray();
        }
        if (!is_array($childs)) {
            $childs = [$childs];
        }
        $childs = array_map(function ($child) {
            if (is_object($child) && method_exists($child, 'getUI')) {
                return $child->getUI();
            }
            return $child;
        }, $childs);
        if ($prepend) {
            $this->childs[$group] = array_merge($childs, $this->childs[$group] ?? []);
        } else {
            $this->childs[$group] = array_merge($this->childs[$group] ?? [],  $childs);
        }
        return $this;
    }
    private function childNone($ui, $tap = null, $group = 'default')
    {
        return $this->child((None::make($ui))->cloneUI()->tap($tap), $group);
    }
    public function childWithKey($key,  $tap = null, $group = 'default')
    {
        if (!$key) {
            return $this;
        }
        return $this->childNone(SoUI::getUI($key), $tap, $group);
    }
    public function childWithGroupKey($key,  $tap = null, $group = 'default')
    {
        return $this->childNone(SoUI::getGroupUI($key), $tap, $group);
    }

    private function getHtmlItem($ui, $params = null, $callback = null)
    {
        $html = '';
        if (is_array($ui)) {
            $html = $this->getHtmlItems($ui, $params, $callback);
        }
        if (!is_string($ui) && is_callable($ui)) {
            $ui = call_user_func($ui, $this);
        }
        if ($ui instanceof BaseUI) {
            $ui->savePramas();
            $rs = null;
            if ($callback) {
                $rs = call_user_func($callback, $ui);
            }
            $ui->setParams($params);
            $ui->beforeRender();
            $ui->render();
            if ($ui->checkWhen()) {
                $html = $ui->view();
            }
            $ui->afterRender();
            if ($rs && is_callable($rs)) {
                call_user_func($rs, $ui);
            }
            $ui->restorePramas();
        } elseif (is_string($ui)) {
            $html = $ui;
        } elseif (is_object($ui) && enum_exists(get_class($ui))) // check $ui is enum
        {
            if (method_exists($ui, 'toString')) {
                $html = $ui->toString();
            } else {
                $html = $ui->name;
            }
        }

        return $html;
    }
    protected function getHtmlItems($uis, $params = null, $callback = null)
    {
        $html = '';
        foreach ($uis as $ui) {
            $html .= $this->getHtmlItem($ui, $params, $callback);
        }
        return $html;
    }
    public function renderChilds($group = 'default', $params = null, $callback = null)
    {
        return $this->getHtmlItems($this->childs[$group] ?? [], $params, $callback);
    }
    public function hasChilds($group = 'default')
    {
        return count($this->childs[$group] ?? []) > 0;
    }
}
