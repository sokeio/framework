<?php

namespace Sokeio\Concerns;


trait WithDoAction
{
    use WithHelpers;

    public $__Params = [];
    protected $__request;
    public function bootWithDoAction()
    {
        $this->__request = request();
        if ($this->__request->get('param'))
            $this->__Params = $this->JsonParam($this->__request->get('param'));
    }
    public function getValueBy($key, $default = null)
    {
        if (isset($this->__Params) && $this->__Params && isset($this->__Params[$key])) {
            return $this->__Params[$key];
        }
        return request($key, $default);
    }

    public function DoAction($action, $param)
    {
        $_param = $this->JsonDecode($this->Base64Decode($param));
        $this->__Params = array_merge($this->__Params, $_param);
        $action = $this->Base64Decode($action);
        return  app($action)->SetComponent($this)->SetParam($this->__Params)->DoAction();
    }
}
