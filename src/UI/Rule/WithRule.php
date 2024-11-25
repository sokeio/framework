<?php

namespace Sokeio\UI\Rule;


trait WithRule
{
    protected $managerRule = null;
    protected $whenRuleCallbacks = null;
    public function checkRuleField()
    {
        if (!$this->managerRule) {
            return false;
        }
        return $this->managerRule->check();
    }
    public function whenRule($callback)
    {
        $this->whenRuleCallbacks = $callback;
        return $this;
    }
    private function checkWhenRule()
    {
        if ($this->whenRuleCallbacks) {
            return call_user_func($this->whenRuleCallbacks, $this);
        }
        return true;
    }
    public function getRules()
    {
        if (!$this->managerRule || !$this->checkWhenRule()) {
            return [];
        }
        return $this->managerRule->getRules();
    }
    public function getRuleMessages()
    {
        if (!$this->managerRule || !$this->checkWhenRule()) {
            return [];
        }
        return $this->managerRule->getRuleMessages();
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
    public function ruleRequired($message = null, $params = [], $callback = null)
    {
        return $this->rule('required', $message, $params, $callback);
    }
    public function ruleUnique($message = null, $params = [], $callback = null, $key = null, $table = null)
    {
        return $this->register(function () use ($key, $table, $message, $params, $callback) {
            if (!$table) {
                $table = app($this->getWire()->getModel())->getTable();
            }
            if (!$key) {
                $key = $this->getFieldNameWithoutPrefix();
            }
            if ($this->getWire()->dataId) {
                $key .= ',' . $this->getWire()->dataId;
            }
            return $this->rule('unique:' . $table . ',' . $key, $message, $params, $callback);
        });
    }

    public function ruleEmail($message = null, $params = [], $callback = null)
    {
        return $this->rule('email', $message, $params, $callback);
    }
    public function ruleMax($max, $message = null, $params = [], $callback = null)
    {
        return $this->rule('max:' . $max, $message, $params, $callback);
    }
    public function ruleMin($min, $message = null, $params = [], $callback = null)
    {
        return $this->rule('min:' . $min, $message, $params, $callback);
    }
    public function ruleBetween($min, $max, $message = null, $params = [], $callback = null)
    {
        return $this->rule('between:' . $min . ',' . $max, $message, $params, $callback);
    }

    public function ruleInteger($message = null, $params = [], $callback = null)
    {
        return $this->rule('integer', $message, $params, $callback);
    }
    public function ruleBoolean($message = null, $params = [], $callback = null)
    {
        return $this->rule('boolean', $message, $params, $callback);
    }
    public function ruleDate($message = null, $params = [], $callback = null)
    {
        return $this->rule('date', $message, $params, $callback);
    }
    public function ruleDateTime($message = null, $params = [], $callback = null)
    {
        return $this->rule('datetime', $message, $params, $callback);
    }
    public function ruleNumeric($message = null, $params = [], $callback = null)
    {
        return $this->rule('numeric', $message, $params, $callback);
    }
    public function ruleAlpha($message = null, $params = [], $callback = null)
    {
        return $this->rule('alpha', $message, $params, $callback);
    }
    public function ruleAlphaNum($message = null, $params = [], $callback = null)
    {
        return $this->rule('alpha_num', $message, $params, $callback);
    }
    public function ruleAlphaDash($message = null, $params = [], $callback = null)
    {
        return $this->rule('alpha_dash', $message, $params, $callback);
    }
    public function ruleAlphaNumDash($message = null, $params = [], $callback = null)
    {
        return $this->rule('alpha_num_dash', $message, $params, $callback);
    }
    public function ruleAlphaNumSpace($message = null, $params = [], $callback = null)
    {
        return $this->rule('alpha_num_space', $message, $params, $callback);
    }
    public function rulePhone($message = null, $regex = '/^1[3456789]\d{9}$/', $params = [], $callback = null)
    {
        return $this->rule('regex:' . $regex, $message, $params, $callback);
    }
}
