<?php

namespace Sokeio\Components;

use Sokeio\Components\Concerns\WithBaseBasic;
use Sokeio\Laravel\BaseCallback;

class Base extends BaseCallback
{
    use WithBaseBasic;
    protected function childComponents()
    {
        return [];
    }
    private $cacheComponents;
    protected function clearComponents($arr)
    {
        $result = [];
        if (!$arr) {
            return $result;
        }
        if (is_array($arr)) {
            foreach ($arr as $value) {
                if (is_array($value)) {
                    $result = [...$result, ...$this->clearComponents($value)];
                } elseif (is_a($value, Base::class)) {
                    $result[] = $value;
                }
            }
        } else {
            if (is_a($arr, Base::class)) {
                $result[] = $arr;
            }
        }

        return $result;
    }
    private function getChildComponents()
    {
        if (!isset($this->cacheComponents)) {
            $this->cacheComponents = $this->clearComponents($this->childComponents());
        }
        return $this->cacheComponents;
    }
    private $callbackReady = [];
    public function ready($callback)
    {
        if (!is_callable($callback)) {
            return $this;
        }
        $this->callbackReady[] = $callback;
        return $this;
    }
    public function boot()
    {
        $this->ClearCache();
        foreach ($this->getChildComponents() as $component) {
            $component->Manager($this->getManager());
            $component->prex($this->getPrex());
            $component->boot();
        }
        foreach ($this->callbackReady as $callback) {
            if (!is_callable($callback)) {
                continue;
            }
            $callback($this);
        }
        // Reset Callback
        $this->callbackReady = [];
    }

    public static function make($value)
    {
        return new static($value);
    }
    public function actionUI($key, $callbackUI, $over = false): static
    {
        $this->ready(function ($component) use ($key, $callbackUI, $over) {
            $component->getManager()->addActionUI($key, $callbackUI, $over);
        });
        return $this;
    }
}
