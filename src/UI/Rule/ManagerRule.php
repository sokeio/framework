<?php

namespace Sokeio\UI\Rule;

use Sokeio\UI\Field\FieldUI;

class ManagerRule
{
    private $rules = [];
    public function __construct(private FieldUI $field) {}
    public function rule($rule, $message = null, $params = [], $callback = null)
    {
        $filedRule = new FieldRule($this, $rule, $message, $params);
        if ($callback && is_callable($callback)) {
            call_user_func($callback, $filedRule);
        }
        $this->rules[] = $filedRule;
        return $this;
    }
    public function required($message = null, $params = [],)
    {
        return $this->rule('required', $message, $params);
    }
    public function tap($callback)
    {
        if ($callback && is_callable($callback)) {
            $callback($this);
        }
        return $this;
    }
    public function check()
    {
        return !empty($this->rules);
    }
    public function getRules()
    {
        return [
            $this->field->getFieldName() => collect($this->rules)
                ->map(function (FieldRule $rule) {
                    return $rule->getRule();
                })->toArray()
        ];
    }
    public function getRuleMessages()
    {
        return [
            $this->field->getFieldName() => collect($this->rules)
                ->reduce(function ($rs, FieldRule $rule) {
                    $rs[$rule->getRule()] = $rule->getMessage();
                    return $rs;
                }, [])
        ];
    }
}
