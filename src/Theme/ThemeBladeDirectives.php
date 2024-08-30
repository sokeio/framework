<?php

namespace Sokeio\Theme;

class ThemeBladeDirectives
{
    public static function themeBodyEnd()
    {
        return <<<EOT
        <?php
            \Sokeio\Theme::bodyEndRender();
        ?>
        EOT;
    }
    public static function themeBody()
    {
        return <<<EOT
        <?php
            \Sokeio\Theme::bodyRender();
        ?>
        EOT;
    }

    public static function themeHead()
    {
        return <<<EOT
        <?php
            \Sokeio\Theme::headRender();
        ?>
        EOT;
    }
    public static function themeInclude($expression)
    {
        return <<<EOT
        <?php
            \Sokeio\Theme::include("{$expression}");
        ?>
        EOT;
    }
}
