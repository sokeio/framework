<?php

namespace Sokeio\UI\Rule;


trait WithRule
{
    protected $managerRule = null;
    public function checkRuleField()
    {
        if (!$this->managerRule) {
            return false;
        }
        return $this->managerRule->check();
    }
    public function getRules()
    {
        if (!$this->managerRule) {
            return [];
        }
        return $this->managerRule->getRules();
    }
    public function getManagerRule(): ManagerRule|null
    {
        return $this->managerRule;
    }
    public function tapRule($callback)
    {
        if (!$this->managerRule) {
            $this->managerRule = new ManagerRule($this);
        }
        $this->managerRule->tap($callback);
        return $this;
    }
    public function rule($rule, $message = null, $params = [], $callback = null)
    {
        return $this->tapRule(fn(ManagerRule $managerRule) => $managerRule->rule($rule, $message, $params, $callback));
    }
}
