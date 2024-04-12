<?php

namespace Sokeio\Dashboard;

use Illuminate\Support\Traits\Macroable;
use Sokeio\Platform\PlatformStatus;

class Widget
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

    public function Active()
    {
        return $this->getStatus()->active($this->key);
    }

    public function block()
    {
        return $this->getStatus()->block($this->key);
    }
    private $action = [];
    public function Action($action, $callback)
    {
        $this->action[$action] = $callback;
        return $this;
    }
    public function callAction($action, $params)
    {
        if (isset($this->action[$action]))
            return call_user_func($this->action[$action], ...$params);
    }
    private $component;
    public function Component($component)
    {
        $this->component = $component;
        return $this;
    }
    public function getComponent()
    {
        return $this->component;
    }
    private $params;
    public function Params($params)
    {
        $this->params = $params;
        return $this;
    }
    public function getParams()
    {
        return $this->params;
    }
    private $name;
    public function Name($name)
    {
        $this->name = $name;
        return $this;
    }
    public function getName()
    {
        return  $this->name;
    }
    private $view;
    public function View($view)
    {
        $this->view = $view;
        return $this;
    }
    public function getView()
    {
        return  $this->view;
    }
    private $data;
    public function Data($data)
    {
        $this->data = $data;
        return $this;
    }
    public function getData()
    {
        if ($this->data) {
            if (is_callable($this->data)) return call_user_func($this->data, $this);
            else return $this->data;
        }
        return [];
    }
    private $column;
    public function Column($column)
    {
        $this->column = $column;
        return $this;
    }
    public function getColumn()
    {
        return  $this->column;
    }
    private $poll;
    public function Poll($poll)
    {
        $this->poll = $poll;
        return $this;
    }
    public function getPoll()
    {
        return  $this->poll;
    }

    public function WidgetNumber()
    {
        return $this->View('sokeio::widgets.number-widget');
    }
    public function WidgetApexcharts()
    {
        return $this->View('sokeio::widgets.apexcharts-widget');
    }
}
