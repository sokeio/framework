<?php

namespace Sokeio\UI\Support;

use Illuminate\Support\Facades\Log;

class HookUI
{
    private $hook = [];

    public function callback($callback)
    {
        $this->hook[] = $callback;
    }

    public function run($parameters)
    {
        foreach ($this->hook ?? [] as $callback) {
            if (is_array($parameters) && count($parameters) > 0) {
                call_user_func_array($callback, $parameters);
            } else {
                call_user_func($callback);
            }
        }
        return $this;
    }
    public function group($key)
    {
        if (!isset($this->hook[$key])) {
            $this->hook[$key] = new HookUI();
        }
        return $this->hook[$key];
    }
}
