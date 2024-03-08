<?php

namespace Sokeio\Shortcode;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Livewire;

class Shortcode implements Arrayable
{
    /**
     * Shortcode name
     *
     * @var string
     */
    protected $name;

    /**
     * Shortcode Attributes
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Shortcode content
     *
     * @var string
     */
    public $content;

    /**
     * Shortcode viewData
     *
     * @var array
     */
    protected $viewData;

    /**
     * Shortcode Callback
     *
     * @var array|string|null|callable
     */
    protected $callbacks;

    /**
     * Shortcode manager
     *
     * @var ShortcodeManager
     */
    protected $manager;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $content
     * @param array  $attributes
     */
    public function __construct($name, $content, $attributes = [], $viewData = [], $callbacks = [], $manager = null)
    {
        $this->name = $name;
        $this->content = $content;
        $this->attributes = $attributes;
        $this->callbacks = $callbacks;
        $this->viewData = $viewData;
        $this->manager = $manager;
    }

    /**
     * Get html attribute
     *
     * @param  string $attribute
     *
     * @return string|null
     */
    public function get($attribute, $fallback = null)
    {
        $value = $this->{$attribute};
        if (!is_null($value)) {
            return $attribute . '="' . $value . '"';
        } elseif (!is_null($fallback)) {
            return $attribute . '="' . $fallback . '"';
        }
    }

    /**
     * Get shortcode name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get shortcode attributes
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Return array of attributes;
     *
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * Dynamically get attributes
     *
     * @param  string $param
     *
     * @return string|null
     */
    public function __get($param)
    {
        return isset($this->attributes[$param]) ? $this->attributes[$param] : null;
    }
    public function render()
    {
        if (isset($this->callbacks) && is_string($this->callbacks) && is_a($this->callbacks, Component::class, true)) {
            return Livewire::mount('shortcode::' . $this->getName(), $this->attributes ?? []);
        }
        Log::info($this->callbacks);
        // Render the shortcode through the callback
        return call_user_func_array($this->callbacks, [
            $this,
        ]);
    }
}
