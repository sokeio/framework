<?php

namespace Sokeio\Components\Concerns;

trait WithActionUI
{
    private $actionUI = [];
    public function addActionUI($actonKey, $actonFn)
    {
        if (!isset($this->actionUI[$actonKey])) {
            $this->actionUI[$actonKey] = $actonFn;
        }
        return $this;
    }
    public function callActionUI($actonKey, ...$arg)
    {
        if (isset($this->actionUI[$actonKey])) {
            return call_user_func($this->actionUI[$actonKey], $this, ...$arg);
        }
        if (method_exists($this, $actonKey)) {
            return call_user_func([$this, $actonKey], ...$arg);
        }
        return null;
    }
}
