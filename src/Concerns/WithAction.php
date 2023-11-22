<?php

namespace Sokeio\Concerns;

use ReflectionMethod;

trait WithAction
{
    use WithHelpers;
    public function __invoke()
    {
        return $this->callMethodWithParam(func_get_args());
    }
    public function handle()
    {
        return $this->callMethodWithParam(func_get_args());
    }
    public function handleWithParams($params)
    {
        return $this->callMethodWithParam($params);
    }
    public function DoAction()
    {
    }
    private function callMethodWithParam($params)
    {
        $arr = [];
        $r = new ReflectionMethod($this, 'DoAction');
        $methodParams = $r->getParameters();
        foreach ($methodParams as $param) {
            if (isset($params[$param->getName()])) {
                $arr[] = $params[$param->getName()];
            } else if ($varReq = request($param->getName())) {
                $arr[] = $varReq;
            } else if ($param->hasType() && $app = app($param->getType())) {
                $arr[] = $app;
            } else {
                $arr[] = null;
            }
        }

        return $this->DoAction(...$arr);
    }
}
