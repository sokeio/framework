<?php

namespace Sokeio\Shortcode;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Livewire;
use Sokeio\Concerns\WithHelpers;

class ShortcodeManager
{
    protected $registered = [];
    use WithHelpers;
    public function getAllShortcodeFromText($shortcode)
    {
        if (!$shortcode) return null;
        $pattern = '/\[([\w-:]+)((?:\s+\w+\s*=\s*"[^"]*")*)\](.*?)\[\/\1\]/s';
        $pattern = '/\[([\w-:]+)((?:\s+\w+\s*=\s*"[^"]*")*)\](.*?)\[\/\1\]/s';
        // Extract all shortcode matches
        preg_match_all($pattern, $shortcode, $matches, PREG_SET_ORDER);

        $shortcodeObjects = [];

        foreach ($matches as $match) {
            $shortcodeName = $match[1];
            $attributesString = $match[2];
            $shortcodeContent = $match[3];

            // Regular expression pattern to match attribute-value pairs
            $attributePattern = '/(\w+)\s*=\s*"([^"]*)"/';

            // Extract attributes using preg_match_all()
            preg_match_all($attributePattern, $attributesString, $attributeMatches, PREG_SET_ORDER);

            // Create an array to store attribute-value pairs
            $attributes = [];

            // Iterate over attribute matches and populate the attributes array
            foreach ($attributeMatches as $attributeMatch) {
                $attribute = $attributeMatch[1];
                $value = $attributeMatch[2];
                $attributes[$attribute] = $value;
            }

            // Create shortcode object
            $shortcodeObject = [
                'shortcode' => $shortcodeName,
                'attributes' => $attributes,
                'content' => $shortcodeContent
            ];

            // Add shortcode object to the list
            $shortcodeObjects[] = $shortcodeObject;
        }

        return $shortcodeObjects;
    }
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

    protected $_viewData;

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
     * Compile the contents
     *
     * @param  string $value
     *
     * @return string
     */
    public function compile($value)
    {
        // Only continue is laravel-shortcodes have been registered
        if (!$this->enabled || !$this->hasShortcodes()) {
            return $value;
        }
        // Set empty result
        $result = '';
        // Here we will loop through all of the tokens returned by the Zend lexer and
        // parse each one into the corresponding valid PHP. We will then have this
        // template as the correctly rendered PHP that can be rendered natively.
        foreach (token_get_all($value) as $token) {
            $result .= is_array($token) ? $this->parseToken($token) : $token;
        }

        return $result;
    }
    public function compileOnly($value)
    {
        $flg = $this->getStatus();
        $content = $this->compile($value);
        if (!$flg) $this->disable();
        // return compiled contents
        return $content;
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

    /**
     * Parse the tokens from the template.
     *
     * @param  array $token
     *
     * @return string
     */
    protected function parseToken($token)
    {
        list($id, $content) = $token;
        if ($id == T_INLINE_HTML) {
            $content = $this->renderShortcodes($content);
        }

        return $content;
    }

    /**
     * Render laravel-shortcodes
     *
     * @param  string $value
     *
     * @return string
     */
    protected function renderShortcodes($value)
    {
        $pattern = $this->getRegex();

        return preg_replace_callback("/{$pattern}/s", [$this, 'render'], $value);
    }

    // get view data
    public function viewData($viewData)
    {
        $this->_viewData = $viewData;
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
     * Get Compiled Attributes.
     *
     * @param $matches
     *
     * @return \Sokeio\Cms\Shortcode\Shortcode
     */
    protected function compileShortcode($matches)
    {
        // Set matches
        $this->setMatches($matches);
        // pars the attributes
        $attributes = $this->parseAttributes($this->matches[3]);

        // return shortcode instance
        return new Shortcode(
            $this->getName(),
            $this->getContent($this->getName()),
            $attributes,
            $this->_viewData,
            $this->getCallback($this->getName())
        );
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
            $content = $this->Base64Decode($this->matches[5]);
            $short = $this->registered[$name];
            if ($short && is_object($short) && method_exists($short, 'getStripTags')) {
                if ($short->getStripTags()) {
                    $content = preg_replace('/<[^>]*>/', '', $content);
                }
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
     * Get the callback for the current shortcode (class or callback)
     *
     * @param  string $name
     *
     * @return callable|array
     */
    public function getCallback($name)
    {
        // Get the callback from the laravel-shortcodes array
        $callback = $this->registered[$name];
        // if is a string
        if (is_string($callback)) {
            if (is_a($callback, Component::class, true))
                return $callback;
            // Parse the callback
            list($class, $method) = Str::parseCallback($callback, 'renderHtml');
            // If the class exist
            if (class_exists($class)) {
                // return class and method
                return [
                    app($class),
                    $method
                ];
            }
        }
        if (is_object($callback)) {
            return [
                $callback,
                'renderHtml'
            ];
        }
        return $callback;
    }

    /**
     * Parse the shortcode attributes
     *
     * @author Wordpress
     * @return array
     */
    protected function parseAttributes($text)
    {
        // decode attribute values
        $text = htmlspecialchars_decode($text, ENT_QUOTES);

        $attributes = [];
        // attributes pattern
        $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
        // Match
        if (preg_match_all($pattern, preg_replace('/[\x{00a0}\x{200b}]+/u', " ", $text), $match, PREG_SET_ORDER)) {
            foreach ($match as $m) {
                if (!empty($m[1])) {
                    $attributes[strtolower($m[1])] = stripcslashes($m[2]);
                } elseif (!empty($m[3])) {
                    $attributes[strtolower($m[3])] = stripcslashes($m[4]);
                } elseif (!empty($m[5])) {
                    $attributes[strtolower($m[5])] = stripcslashes($m[6]);
                } elseif (isset($m[7]) && strlen($m[7])) {
                    $attributes[] = stripcslashes($m[7]);
                } elseif (isset($m[8])) {
                    $attributes[] = stripcslashes($m[8]);
                }
            }
        } else {
            $attributes = ltrim($text);
        }

        // return attributes
        return is_array($attributes) ? $attributes : [$attributes];
    }

    /**
     * Get shortcode names
     *
     * @return string
     */
    protected function getShortcodeNames()
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
        $shortcodeNames = $this->getShortcodeNames();

        return "\\[(\\[?)($shortcodeNames)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)";
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
        $this->registered[($shortocde)::getShortcodeKey()] = $shortocde;
        if (is_a($shortocde, Component::class, true)) {
            Livewire::component('shortcode::' . ($shortocde)::getShortcodeKey(), $shortocde);
        }
    }
}
