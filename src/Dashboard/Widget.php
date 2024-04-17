<?php

namespace Sokeio\Dashboard;

use Illuminate\Support\Traits\Macroable;
use Sokeio\Laravel\BaseCallback;
use Sokeio\Platform\PlatformStatus;

class Widget extends BaseCallback
{
    use Macroable;
    private const DASHBOARD_WIDGET_STATUS = 'DASHBOARD_WIDGET_STATUS';
    public function __construct(protected $key)
    {
    }
    public function getKey()
    {
        return $this->key;
    }
    private function getStatus()
    {
        return PlatformStatus::key(self::DASHBOARD_WIDGET_STATUS);
    }
    public function isActive()
    {
        return $this->getStatus()->check($this->key);
    }

    public function active()
    {
        return $this->getStatus()->active($this->key);
    }

    public function block()
    {
        return $this->getStatus()->block($this->key);
    }
    private $callbackReady = [];
    public function ready($callback)
    {
        if (!is_callable($callback)) {
            return $this;
        }
        $this->callbackReady[] = $callback;
        return $this;
    }
    public function doReady()
    {
        foreach ($this->callbackReady as $callback) {
            call_user_func($callback, $this, $this->getComponent());
        }
    }
    public function action($action, $callback)
    {
        return $this->ready(function ($widget) use ($action, $callback) {
            $widget->getComponent()->addActionUI($action, $callback);
        });
    }
    private $component = null;
    public function component($component): static
    {
        $this->component = $component;
        $this->doReady();
        return $this;
    }
    public function getComponent()
    {
        return $this->component;
    }
    public function params($params): static
    {
        return $this->setKeyValue('params', $params);
    }
    public function getParams()
    {
        return $this->getValue('params');
    }
    public function name($name): static
    {
        return $this->setKeyValue('name', $name);
    }
    public function getName()
    {
        return $this->getValue('name');
    }
    public function view($view): static
    {
        return $this->setKeyValue('view', $view);
    }
    public function getView()
    {
        return $this->getValue('view');
    }
    public function data($data): static
    {
        return $this->setKeyValue('data', $data);
    }
    public function getData()
    {

        return $this->getValue('data');
    }
    public function column($column): static
    {
        return $this->setKeyValue('column', $column);
    }
    public function getColumn()
    {
        return  $this->getValue('column');
    }
    public function poll($poll): static
    {
        return $this->setKeyValue('poll', $poll);
    }
    public function getPoll()
    {
        return $this->getValue('poll');
    }

    public function widgetNumber()
    {
        return $this->view('sokeio::widgets.number-widget');
    }
    public function widgetApexcharts()
    {
        return $this->view('sokeio::widgets.apexcharts-widget');
    }
}
