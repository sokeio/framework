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

    private function callMethodWithParam($params)
    {
        $arr = [];
        $r = new ReflectionMethod($this, 'doAction');
        $methodParams = $r->getParameters();
        foreach ($methodParams as $param) {
            if (isset($params[$param->getName()])) {
                $arr[] = $params[$param->getName()];
            } elseif ($varReq = request($param->getName())) {
                $arr[] = $varReq;
            } elseif ($param->hasType() && $app = app($param->getType())) {
                $arr[] = $app;
            } else {
                $arr[] = null;
            }
        }

        return  call_user_func([$this, 'doAction'], ...$arr);
    }
}
