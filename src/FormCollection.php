<?php

namespace BytePlatform;

use BytePlatform\Laravel\WithHookListener;

class FormCollection
{

    use WithHookListener;
    /**
     * Filters a value
     *
     * @param  string  $action Name of filter
     * @param  array  $args Arguments passed to the filter
     * @return mixed Always returns the value
     */
    public function fire(string $action, array $args)
    {
        $value = $args[0] ?? ''; // get the value, the first argument is always the value
        if (!$this->getListeners()) {
            return $value;
        }

        foreach ($this->getListeners() as $hook => $listeners) { // go through each of the priorities
            ksort($listeners);
            foreach ($listeners as $arguments) { // loop all hooks
                if ($hook === $action) { // if the hook responds to the current filter
                    $parameters = [$value];
                    for ($index = 1; $index < $arguments['arguments']; $index++) {
                        if (isset($args[$index])) {
                            $parameters[] = $args[$index]; // add arguments if it is there
                        }
                    }
                    // filter the value
                    $value = call_user_func_array($this->getFunction($arguments['callback']), $parameters);
                }
            }
        }

        return $value;
    }
    public function Register($callback, $form = 'overview')
    {
        $this->addListener($form, $callback);
        return $this;
    }
    public function getForms()
    {
        return array_keys($this->getListeners());
    }
    public function getFormWithTitles()
    {
        return collect($this->getForms())->map(function ($key) {
            return [
                'key' => $key,
                'title' => $this->getFormByKey($key)->getTitle()
            ];
        });
    }
    public function getFormByKey($key = 'overview')
    {
        return $this->fire($key, [ItemManager::Form()->Title('Overview')]);
    }
    public static function Create()
    {
        return new self();
    }
}
