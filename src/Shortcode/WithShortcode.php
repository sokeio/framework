<?php

namespace Sokeio\Shortcode;

use Sokeio\Facades\Platform;
use Sokeio\Facades\Shortcode;
use Sokeio\Laravel\WithCallback;

trait WithShortcode
{
    use WithCallback;
    public static function register()
    {
        Shortcode::register(static::class);
    }
    public static function getTitle()
    {
        return 'title';
    }
    public static function getKey()
    {
        return 'key';
    }
    public static function getParamUI()
    {
        return [];
    }
    public static function withChild()
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
        return '';
    }
}
