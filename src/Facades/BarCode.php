<?php

namespace BytePlatform\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void OutputImage($format, $symbology, $data, $options)
 * @method static mix RenderImage($symbology, $data, $options)
 * @method static mix RenderSvg($symbology, $data, $options)
 *
 * @see \BytePlatform\Facades\BarCode
 */
class BarCode extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \BytePlatform\Support\BarCode\BarCodeManager::class;
    }
}
