<?php

namespace Sokeio\Directives;

use Illuminate\Support\Str;

class PlatformBladeDirectives
{
    public static function EndRole()
    {
        return <<<EOT
        <?php
            endif;
        ?>
        EOT;
    }
    public static function Role($role)
    {
        return <<<EOT
        <?php
        if(checkRole('{$role}'))) :
        ?>
        EOT;
    }
    public static function EndPermission()
    {
        return <<<EOT
        <?php
            endif;
        ?>
        EOT;
    }
    public static function Permission($permission)
    {
        return <<<EOT
        <?php
      
        if(checkPermission('{$permission}'))) :
        ?>
        EOT;
    }
    public static function ThemeHead($expression)
    {
        $expression = Str::upper($expression);
        return <<<EOT
        <?php
            do_action('PLATFORM_HEAD_{$expression}');
        ?>
        EOT;
    }

    public static function ThemeBody($expression)
    {
        $expression = Str::upper($expression);
        return <<<EOT
        <?php
        do_action('PLATFORM_BODY_{$expression}');
        ?>
        EOT;
    }
}
