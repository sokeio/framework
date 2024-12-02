<?php

namespace Sokeio\UI\Support\Concerns;


trait WithAlpine
{
    
    private function x($name, $value = null): self
    {
        $this->ui->boot(function () use ($name, $value) {
            $this->ui->attr('x-' . $name, $value);
        });
        return $this;
    }
    public function data($value = null)
    {
        return $this->x('data', $value);
    }
    public function init($value = null)
    {
        return $this->x('init', $value);
    }
    public function show($value = null)
    {
        return $this->x('show', $value);
    }
    public function bind($value = null)
    {
        return $this->x('bind', $value);
    }
    public function on($value = null, $event = 'click')
    {
        return $this->x('on:' . $event, $value);
    }
    public function text($value = null)
    {
        return $this->x('text', $value);
    }
    public function html($value = null)
    {
        return $this->x('html', $value);
    }
    public function model($value = null)
    {
        return $this->x('model', $value);
    }
    public function modelable($value = null)
    {
        return $this->x('modelable', $value);
    }
    public function for($value = null)
    {
        return $this->x('for', $value);
    }
    public function transition($value = null)
    {
        return $this->x('transition', $value);
    }
    public function effect($value = null)
    {
        return $this->x('effect', $value);
    }
    public function ignore($value = null)
    {
        return $this->x('ignore', $value);
    }
    public function ref($value = null)
    {
        return $this->x('ref', $value);
    }
    public function cloak($value = null)
    {
        return $this->x('cloak', $value);
    }
    public function teleport($value = null)
    {
        return $this->x('teleport', $value);
    }
    public function if($value = null)
    {
        return $this->x('if', $value);
    }
    public function id($value = null)
    {
        return $this->x('id', $value);
    }
}
