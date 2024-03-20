<?php

namespace Sokeio\Concerns;


trait WithDoAction
{
    use WithHelpers;

    public $actionParams = [];
    protected $request;
    public function bootWithDoAction()
    {
        $this->request = request();
        if ($this->request->get('param')) {
            $this->actionParams = $this->jsonParam($this->request->get('param'));
        }
    }
    public function getValueBy($key, $default = null)
    {
        if (isset($this->actionParams) && $this->actionParams && isset($this->actionParams[$key])) {
            return $this->actionParams[$key];
        }
        return request($key, $default);
    }

    public function doAction($action, $param)
    {
        $_param = $this->jsonDecode($this->base64Decode($param));
        $this->actionParams = array_merge($this->actionParams, $_param);
        $action = $this->base64Decode($action);
        return  app($action)->SetComponent($this)->SetParam($this->actionParams)->doAction();
    }
}
