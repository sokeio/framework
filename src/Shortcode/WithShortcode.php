<?php

namespace Sokeio\Shortcode;

use Sokeio\Laravel\WithCallback;

trait WithShortcode
{
    use WithCallback;
    public static function getShortcodeName()
    {
    }
    public static function getShortcodeKey()
    {
    }
    public static function getShortcodeParamUI()
    {
        return [];
    }
    public static function EnableContent()
    {
        return true;
    }

    public $shortcodeName;
    public $shortcodeAttrs;
    public $shortcodeContent;
    public $shortcodeViewData;

    protected function getAttributeValue($key)
    {
        if (isset($this->shortcodeAttrs[$key])) {
            return $this->shortcodeAttrs[$key];
        }
        return null;
    }


    public function renderHtml()
    {
    }
}
