<?php

namespace Sokeio\Directives;

use Illuminate\Support\Str;

class PlatformBladeDirectives
{
    public static function endRole()
    {
        return <<<EOT
        <?php
            endif;
        ?>
        EOT;
    }
    public static function role($role)
    {
        return <<<EOT
        <?php
        if(checkRole('{$role}'))) :
        ?>
        EOT;
    }
    public static function endPermission()
    {
        return <<<EOT
        <?php
            endif;
        ?>
        EOT;
    }
    public static function permission($permission)
    {
        return <<<EOT
        <?php
      
        if(checkPermission('{$permission}'))) :
        ?>
        EOT;
    }
    public static function themeHead($expression)
    {
        $expression = Str::upper($expression);
        return <<<EOT
        <?php
            doAction('PLATFORM_HEAD_{$expression}');
        ?>
        EOT;
    }

    public static function themeBody($expression)
    {
        $expression = Str::upper($expression);
        return <<<EOT
        <?php
        doAction('PLATFORM_BODY_{$expression}');
        ?>
        EOT;
    }
}
