<?php

namespace Sokeio\Shortcode;

use Sokeio\Facades\Shortcode;
use Sokeio\Laravel\WithCallback;

trait WithShortcode
{
    use WithCallback;
    public static function Register()
    {
        Shortcode::register(static::class);
    }
    public static function getTitle()
    {
    }
    public static function getKey()
    {
    }
    public static function getParamUI()
    {
        return [];
    }
    public static function EnableChild()
    {
        return false;
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
