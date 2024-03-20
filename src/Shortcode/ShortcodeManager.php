<?php

namespace Sokeio\Shortcode;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Livewire;
use Sokeio\Concerns\WithHelpers;

class ShortcodeManager
{
    protected $registered = [];
    use WithHelpers;
    use WithShortcodeProcess;

    /**
     * Enabled state
     *
     * @var boolean
     */
    protected $enabled = false;

    /**
     * Enable strip state
     *
     * @var boolean
     */
    protected $strip = false;

    /**
     * @var
     */
    protected $matches;

    /**
     * Attached View Data
     *
     * @var array
     */
    protected $data = [];

    protected $viewData;

    /**
     * Enable
     *
     * @return void
     */
    public function enable()
    {
        $this->enabled = true;
    }

    /**
     * Disable
     *
     * @return void
     */
    public function disable()
    {
        $this->enabled = false;
    }
    public function getStatus()
    {
        return $this->enabled;
    }
    public function attachData($data)
    {
        $this->data = $data;
    }


    /**
     * Check if laravel-shortcodes have been registered
     *
     * @return boolean
     */
    public function hasShortcodes()
    {
        return !empty($this->registered);
    }

    // get view data
    public function viewData($viewData)
    {
        $this->viewData = $viewData;
        return $this;
    }

    /**
     * Render the current calld shortcode.
     *
     * @param  array $matches
     *
     * @return string
     */
    public function render($matches)
    {
        return $this->compileShortcode($matches)->render();
    }


    /**
     * Set the matches
     *
     * @param array $matches
     */
    protected function setMatches($matches = [])
    {
        $this->matches = $matches;
    }

    /**
     * Return the shortcode name
     *
     * @return string
     */
    public function getName()
    {
        return $this->matches[2];
    }

    /**
     * Return the shortcode content
     *
     * @return string
     */
    public function getContent($name)
    {
        try {
            $content = $this->base64Decode($this->matches[5]);
            $short = $this->registered[$name];
            if ($short && is_object($short) && method_exists($short, 'getStripTags') && $short->getStripTags()) {
                $content = preg_replace('/<[^>]*>/', '', $content);
            }
            // Compile the content, to support nested laravel-shortcodes
            return $this->compile($content);
        } catch (\Exception $ex) {
            Log::error($ex);
            // Compile the content, to support nested laravel-shortcodes
            return $this->compile($this->matches[5]);
        }
    }

    /**
     * Return the view data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     * Get shortcode names
     *
     * @return string
     */
    protected function getNames()
    {
        return join('|', array_map('preg_quote', array_keys($this->registered)));
    }

    /**
     * Get shortcode regex.
     *
     * @author Wordpress
     * @return string
     */
    protected function getRegex()
    {
        $names = $this->getNames();

        return "\\[(\\[?)($names)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)";
    }

    /**
     * Remove all shortcode tags from the given content.
     *
     * @param string $content Content to remove shortcode tags.
     *
     * @return string Content without shortcode tags.
     */
    public function strip($content)
    {
        if (empty($this->registered)) {
            return $content;
        }
        $pattern = $this->getRegex();

        return preg_replace_callback("/{$pattern}/s", [$this, 'stripTag'], $content);
    }

    /**
     * @return boolean
     */
    public function getStrip()
    {
        return $this->strip;
    }

    /**
     * @param boolean $strip
     */
    public function setStrip($strip)
    {
        $this->strip = $strip;
    }

    /**
     * Remove shortcode tag
     *
     * @param type $m
     *
     * @return string Content without shortcode tag.
     */
    protected function stripTag($m)
    {
        if ($m[1] == '[' && $m[6] == ']') {
            return substr($m[0], 1, -1);
        }

        return $m[1] . $m[6];
    }

    /**
     * Get registered shortcodes
     *
     * @return array shortcode tags.
     */
    public function getRegistered()
    {
        return $this->registered;
    }

    public function getItemByKey($key)
    {
        return is_string($key) && isset($this->registered[$key]) ? $this->registered[$key] : null;
    }
    public function register($shortocde)
    {
        $this->registered[($shortocde)::getKey()] = $shortocde;
        if (is_a($shortocde, Component::class, true)) {
            Livewire::component('shortcode::' . ($shortocde)::getKey(), $shortocde);
        }
    }
}
