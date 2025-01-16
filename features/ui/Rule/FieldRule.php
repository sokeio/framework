<?php

namespace Sokeio\UI\Rule;


class FieldRule
{
    public function __construct(
        private ManagerRule $managerRule,
        private $rule,
        private $message = null,
        private $params = []
    ) {}

    public function getRule()
    {
        return $this->rule;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getParams()
    {
        return $this->params;
    }
}
