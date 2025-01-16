<?php

namespace Sokeio;

use Illuminate\Support\Facades\Facade;
use Sokeio\Theme\TemplateManager;

/**
 * @see \Sokeio\Content\Template
 *
 * @method static mixed getTemplates()
 * @method static void register($template, $content)
 */

class Template extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TemplateManager::class;
    }
}
