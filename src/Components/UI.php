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
    public static function getTagUIBy($class_name)
    {
        $inogre_methods = [
            'getTagUI',
            "loadFormfromJson",
            "macro",
            "mixin",
            'getGroup',
            "hasMacro",
            "flushMacros",
            "__callStatic",
            'convertSortableToItems',
            "ready",
            "boot",
            "disableCache",
            "enableCache",
            "clear",
            "clearCache",
            "manager",
            "__call",
            'getTagUIBy'

        ];
        return collect(get_class_methods($class_name))->filter(function ($method) use ($class_name, $inogre_methods) {
            if (
                is_string($method) &&
                method_exists($class_name, $method) &&
                (new \ReflectionMethod($class_name, $method))->isStatic()
                && !in_array($method, $inogre_methods)
            ) {
                return true;
            }
            return false;
        })->map(function ($method) use ($class_name, $inogre_methods) {

            switch ($method) {
                case 'forEach':
                case 'prex';
                    $inst = ($class_name)::{$method}("", []);

                    break;
                default:
                    $inst = ($class_name)::{$method}("");
            }

            $class = get_class($inst);
            $attrs = collect(get_class_methods($class))->filter(function ($method) use ($class, $inogre_methods) {
                if (
                    !str($method)->startsWith('get') &&
                    is_string($method) &&
                    method_exists($class, $method) &&
                    (!(new \ReflectionMethod($class, $method))->isStatic())
                    && !in_array($method, $inogre_methods)
                ) {
                    return true;
                }
                return false;
            })->map(function ($method) use ($class) {
                return [
                    'name' => $method,
                    'params' => collect((new \ReflectionMethod($class, $method))->getParameters())
                        ->map(function ($param) {
                            return [
                                'name' => $param->getName(),
                                'type' => $param->getType()?->getName(),
                                'optional' => $param->isOptional(),
                                'default' => $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null,
                            ];
                        })->values(),
                ];
            })->values();
            return [
                'type' => $method,
                'group' => $inst->getGroup(),
                'class' => $class,
                'attrs' => $attrs,
            ];
        })->values();
    }

    public static function getTagUI()
    {
        return applyFilters("UI_TAG_INFO", static::getTagUIBy(static::class));
    }
}
