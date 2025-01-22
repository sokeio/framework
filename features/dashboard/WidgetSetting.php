<?php

namespace Sokeio\Dashboard;

use Sokeio\Widget;

class WidgetSetting implements \Serializable
{
    public $key;
    public $id;
    public $group;
    public $column;
    public $params;
    private $widget;
    private $columnClass;
    public function getWidget()
    {
        return $this->widget ?? ($this->widget = Widget::getWidget($this->key));
    }
    public function getWidgetUI()
    {
        if (!$this->getWidget()) {
            return null;
        }
        return (data_get($this->getWidget(), 'class'))::make()->setDataParam($this->params);
    }
    public function getColumnClass()
    {
        return $this->columnClass ?? ($this->columnClass = Widget::getColumnClass($this->column));
    }
    public function serialize()
    {
        return json_encode($this->toArray());
    }


    public function fillFromArray($data)
    {
        $this->unserialize(json_encode($data));
        return $this;
    }

    public function unserialize($data)
    {
        $data = json_decode($data);
        $this->key = $data->key;
        $this->id = $data->id;
        $this->group = $data->group;
        $this->column = $data->column;
        $this->params = $data->params;
        return $this;
    }
    public static function parse($data)
    {
        return (new static())->fillFromArray($data);
    }
    public static function make($key, $group, $column, $params = [])
    {
        $inst = new static();
        $inst->key = $key;
        $inst->group = $group;
        $inst->column = $column;
        $inst->params = $params;
        $inst->id =  uniqid('widget-');
        return $inst;
    }
    public function toArray()
    {
        return [
            'key' => $this->key,
            'id' => $this->id,
            'group' => $this->group,
            'column' => $this->column,
            'params' => $this->params
        ];
    }
}
