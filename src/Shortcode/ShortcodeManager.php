<?php

namespace BytePlatform\Shortcode;

class ShortcodeManager
{
    use WithShortcodeCompiler;
    protected $registered = [];
    public function register($shortcodes, $namespace, $force = false)
    {
        if (is_array($shortcodes)) {
            foreach ($shortcodes as $item) {
                $this->registerItem($item->getKey(), $item, $namespace, $force);
            }
            return;
        }
    }
    public function registerItem($key, $itemOrCallback, $namespace, $force = false)
    {
        $shortcodeKey = $namespace . '::' . $key;
        if (!is_callable($itemOrCallback) && method_exists($itemOrCallback, 'NameSpace')) {
            $itemOrCallback->NameSpace($namespace . "::shortcodes.");
        }
        if (!isset($this->registered[$shortcodeKey])) {
            $this->registered[$shortcodeKey] = $itemOrCallback;
            return;
        }
        if (isset($this->registered[$key]) && $force) {
            $this->registered[$shortcodeKey] = $itemOrCallback;
            return;
        }
    }
    public function getShortCodes()
    {
        return $this->registered;
    }

    public function Create($name)
    {
        return ShortcodeItem::Create($name);
    }
}
