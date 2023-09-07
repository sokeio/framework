<?php

namespace BytePlatform\Shortcode;


class Shortcode
{
    /**
     * Shortcode compiler
     *
     * @var ShortcodeManager
     */
    protected $compiler;

    /**
     * Constructor
     *
     * @param ShortcodeManager $compiler
     */
    public function __construct(ShortcodeManager $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * Register a new shortcodes
     *
     * @param  array          $shortcodes
     * @param  callable|string $callback
     * @param  boot $force
     *
     * @return \BytePlatform\Shortcode\Shortcode
     */
    public function register($shortcodes, $namespace, $force = false)
    {
        $this->compiler->register($shortcodes, $namespace, $force);

        return $this;
    }
    /**
     * Register a new shortcodes
     *
     * @param  string          $key
     * @param  callable|string $itemOrCallback
     * @param  string          $namespace
     * @param  boot $force
     *
     * @return \BytePlatform\Shortcode\Shortcode
     */
    public function registerItem($key, $itemOrCallback, $namespace, $force = false)
    {
        $this->compiler->registerItem($key, $itemOrCallback, $namespace, $force);

        return $this;
    }

    /**
     * Enable the laravel-shortcodes
     *
     * @return \BytePlatform\Shortcode\Shortcode
     */
    public function enable()
    {
        $this->compiler->enable();

        return $this;
    }

    /**
     * Disable the laravel-shortcodes
     *
     * @return \BytePlatform\Shortcode\Shortcode
     */
    public function disable()
    {
        $this->compiler->disable();

        return $this;
    }

    /**
     * Compile the given string
     *
     * @param  string $value
     *
     * @return string
     */
    public function compile($value)
    {
        // Always enable when we call the compile method directly
        $this->enable();

        // return compiled contents
        return $this->compiler->compile($value);
    }

    /**
     * Remove all shortcode tags from the given content.
     *
     * @param string $value
     *
     * @return string
     */
    public function strip($value)
    {
        return $this->compiler->strip($value);
    }
    //getShortCodeByKey
    /**
     *
     * @param string $name
     *
     * @return ShortcodeItem
     */
    public function create($name)
    {
        return $this->compiler->create($name);
    }

    /**
     *
     * @param string $name
     *
     * @return ShortcodeItem
     */
    public function getShortCodeByKey($name)
    {
        return $this->compiler->getShortCodeByKey($name);
    }
    /**
     *
     * @param string $name
     *
     * @return mix
     */
    public function getShortCodes()
    {
        return $this->compiler->getShortCodes();
    }
}
