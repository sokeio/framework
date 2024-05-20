<?php

namespace Sokeio\Components;

use Illuminate\Support\Traits\Macroable;
use Sokeio\Components\Concerns\WithCommon;
use Sokeio\Components\Concerns\WithGrid;
use Sokeio\Components\Concerns\WithField;
use Sokeio\Components\Concerns\WithUtils;

class UI
{
    use Macroable, WithGrid, WithCommon, WithField;
    use WithUtils;

    public const KEY_FIELD_NAME = '###______###';

    public const MODAL_SMALL = 'modal-sm';
    public const MODAL_LARGE = 'modal-lg';
    public const MODAL_EXTRA_LARGE = 'modal-xl';
    public const MODAL_FULLSCREEN = 'modal-fullscreen';
    public const MODAL_FULLSCREEN_SM = 'modal-fullscreen-sm-down';
    public const MODAL_FULLSCREEN_MD = 'modal-fullscreen-md-down';
    public const MODAL_FULLSCREEN_LG = 'modal-fullscreen-lg-down';
    public const MODAL_FULLSCREEN_XL = 'modal-fullscreen-xl-down';
    public const MODAL_FULLSCREEN_XXL = 'modal-fullscreen-xxl-down';

    public const BUTTON_SMALL = 'sm';
    public const BUTTON_LARGE = 'lg';
    // {
    //     "type":"div",
    //     "attrs":[],
    //     "children":[
    //         {
    //             "type":"div",
    //             "attrs":[
    //                 {
    //                     "name":"class",
    //                     "value":"btn-group",
    //                     "type":"function"
    //                 }
    //             ],
    //             "children":[
    //                 {
    //                     "type":"div",
    //                     "attrs":[
    //                         {
    //                             "name":"class",
    //                             "value":"btn-group"
    //                         }
    //                     ]

    //         }]}]}
    // }
    public static function loadFormfromJson($json)
    {
        $layout = [];
        if (is_array($json)) {
            foreach ($json as $item) {
                $layout[] = self::loadFormfromJson($item);
            }
        } else {

            if (!isset($json['type'])) {
                $layout = self::{$json['type']}('');
                if ($layout) {
                    $args = isset($json['attrs']) ? $json['attrs'] : [];
                    foreach ($args as $arg) {
                        $value = $arg['value'];
                        if (isset($arg['type']) && $arg['type'] == 'function') {
                            $value = eval($arg['value']);
                        }
                        $layout->{$arg['name']}($value);
                    }
                }
            }
        }
        return $layout;
    }
}
