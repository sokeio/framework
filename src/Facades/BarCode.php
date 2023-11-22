<?php

namespace Sokeio\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void OutputImage($format, $symbology, $data, $options)
 * @method static mix RenderImage($symbology, $data, $options)
 * @method static mix RenderSvg($symbology, $data, $options)
 *
 * @see \Sokeio\Facades\BarCode
 */
class BarCode extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Sokeio\Support\BarCode\BarCodeManager::class;
    }
}
