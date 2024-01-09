<?php

namespace Sokeio\Admin\Components\Field\Concerns;

trait WithFieldRule
{
    public function checkRule()
    {
        return $this->rules && count($this->rules) > 0;
    }
    private $rules = [];
    private $messages = [];
    public function getMessages()
    {
        return $this->messages;
    }
    public function messages($messages): static
    {
        $this->messages[] = $messages;
        return $this;
    }
    public function getRules()
    {
        return $this->rules;
    }
    public function rule($rule, $messages = null): static
    {
        $this->rules[] = $rule;
        if ($messages) {
            $this->messages[$rule] = $messages;
        }
        return $this;
    }
    public function required($messages = null): static
    {
        return $this->rule('required', $messages);
    }
    public function date($messages = null): static
    {
        return $this->rule('date', $messages);
    }
    public function boolean($messages = null): static
    {
        return $this->rule('boolean', $messages);
    }

    public function after($after, $messages = null): static
    {
        return $this->rule('after:' . $after, $messages);
    }
    public function before($before, $messages = null): static
    {
        return $this->rule('before:' . $before, $messages);
    }
    public function between($min, $max, $messages = null): static
    {
        return $this->rule('between:' . $min . ',' . $max, $messages);
    }

    public function unique($table, $id = null, $messages = null): static
    {
        return $this->rule('unique:' . $table . ($id ? ',' . $id : ''), $messages);
    }
    public function max($max, $messages = null): static
    {
        return $this->rule('max:' . $max, $messages);
    }
}
