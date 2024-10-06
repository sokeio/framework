<?php

namespace Sokeio\Concerns;

use Sokeio\Theme;

trait ThemeNone
{
    public function bootThemeNone()
    {
        Theme::setLayout('none');
    }
}
